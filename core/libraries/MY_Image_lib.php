<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Image Manipulation Class fixed
 * fixed method "image_process_gd" to keep png transparency when resizing
 * more info : http://ellislab.com/forums/newreply/77836/
 */
class MY_Image_lib extends CI_Image_lib {


  /**
   * FIXED function to keep transparency
   * Image Process Using GD/GD2
   *
   * This function will resize or crop
   *
   * @access    public
   * @param    string
   * @return    bool
   */
  function image_process_gd($action = 'resize') {
    $v2_override = FALSE;

    // If the target width/height match the source, AND if the new file name is not equal to the old file name
    // we'll simply make a copy of the original with the new name... assuming dynamic rendering is off.
    if ($this->dynamic_output === FALSE) {
      if ($this->orig_width == $this->width AND $this->orig_height == $this->height) {
        if ($this->source_image != $this->new_image) {
          if (@copy($this->full_src_path, $this->full_dst_path)) {
            @chmod($this->full_dst_path, DIR_WRITE_MODE);
          }
        }

        return TRUE;
      }
    }

    // Let's set up our values based on the action
    if ($action == 'crop') {
      //  Reassign the source width/height if cropping
      $this->orig_width = $this->width;
      $this->orig_height = $this->height;

      // GD 2.0 has a cropping bug so we'll test for it
      if ($this->gd_version() !== FALSE) {
        $gd_version = str_replace('0', '', $this->gd_version());
        $v2_override = ($gd_version == 2) ? TRUE : FALSE;
      }
    } else {
      // If resizing the x/y axis must be zero
      $this->x_axis = 0;
      $this->y_axis = 0;
    }

    //  Create the image handle
    if (!($src_img = $this->image_create_gd())) {
      return FALSE;
    }

    //  Create The Image
    //
    //  old conditional which users report cause problems with shared GD libs who report themselves as "2.0 or greater"
    //  it appears that this is no longer the issue that it was in 2004, so we've removed it, retaining it in the comment
    //  below should that ever prove inaccurate.
    //
    //  if ($this->image_library == 'gd2' AND function_exists('imagecreatetruecolor') AND $v2_override == FALSE)
    if ($this->image_library == 'gd2' AND function_exists('imagecreatetruecolor')) {
      $create = 'imagecreatetruecolor';
      $copy = 'imagecopyresampled';
    } else {
      $create = 'imagecreate';
      $copy = 'imagecopyresized';
    }

    // CODE:
    //        $dst_img = $create($this->width, $this->height);
    //        $copy($dst_img, $src_img, 0, 0, $this->x_axis, $this->y_axis, $this->width, $this->height, $this->orig_width, $this->orig_height);
    // REPLACED WITH: start
    // TRANSPARENT PNG
    $dst_img = imagecreatetruecolor($this->width, $this->height);
    $transparent = imagecolorallocatealpha($dst_img, 0, 255, 0, 127);
    imagefill($dst_img, 0, 0, $transparent);
    $copy($dst_img, $src_img, 0, 0, $this->x_axis, $this->y_axis, $this->width, $this->height, $this->orig_width, $this->orig_height);
    imageAlphaBlending($dst_img, false);
    imageSaveAlpha($dst_img, true);
    // REPLACED WITH: end

    //  Show the image
    if ($this->dynamic_output == TRUE) {
      $this->image_display_gd($dst_img);
    } else {
      // Or save it
      if (!$this->image_save_gd($dst_img)) {
        return FALSE;
      }
    }

    //  Kill the file handles
    imagedestroy($dst_img);
    imagedestroy($src_img);

    // Set the file to 777
    @chmod($this->full_dst_path, DIR_WRITE_MODE);

    return TRUE;
  }

  // --------------------------------------------------------------------

  /**
   * Watermark - Graphic Version
   * Agrega la opción para deshabilitar la selección de un píxel transparente
   * @access  public
   * @return  bool
   */
  function overlay_watermark()
  {
    if ( ! function_exists('imagecolortransparent'))
    {
      $this->set_error('imglib_gd_required');
      return FALSE;
    }

    //  Fetch source image properties
    $this->get_image_properties();

    //  Fetch watermark image properties
    $props      = $this->get_image_properties($this->wm_overlay_path, TRUE);
    $wm_img_type  = $props['image_type'];
    $wm_width   = $props['width'];
    $wm_height    = $props['height'];

    //  Create two image resources
    $wm_img  = $this->image_create_gd($this->wm_overlay_path, $wm_img_type);
    $src_img = $this->image_create_gd($this->full_src_path);

    // Reverse the offset if necessary
    // When the image is positioned at the bottom
    // we don't want the vertical offset to push it
    // further down.  We want the reverse, so we'll
    // invert the offset.  Same with the horizontal
    // offset when the image is at the right

    $this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
    $this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));

    if ($this->wm_vrt_alignment == 'B')
      $this->wm_vrt_offset = $this->wm_vrt_offset * -1;

    if ($this->wm_hor_alignment == 'R')
      $this->wm_hor_offset = $this->wm_hor_offset * -1;

    //  Set the base x and y axis values
    $x_axis = $this->wm_hor_offset + $this->wm_padding;
    $y_axis = $this->wm_vrt_offset + $this->wm_padding;

    //  Set the vertical position
    switch ($this->wm_vrt_alignment)
    {
      case 'T':
        break;
      case 'M': $y_axis += ($this->orig_height / 2) - ($wm_height / 2);
        break;
      case 'B': $y_axis += $this->orig_height - $wm_height;
        break;
    }

    //  Set the horizontal position
    switch ($this->wm_hor_alignment)
    {
      case 'L':
        break;
      case 'C': $x_axis += ($this->orig_width / 2) - ($wm_width / 2);
        break;
      case 'R': $x_axis += $this->orig_width - $wm_width;
        break;
    }

    //  Build the finalized image
    if ($wm_img_type == 3 AND function_exists('imagealphablending'))
    {
      @imagealphablending($src_img, TRUE);
    }

    // Set RGB values for text and shadow
    if($this->wm_x_transp>0 AND $this->wm_y_transp>0)
    {
      $rgba = imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp);
      $alpha = ($rgba & 0x7F000000) >> 24;

    }
    else
    {
      $alpha = 9999;
    }

    // make a best guess as to whether we're dealing with an image with alpha transparency or no/binary transparency
    if ($alpha > 0)
    {
      // copy the image directly, the image's alpha transparency being the sole determinant of blending
      imagecopy($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height);
    }
    else
    {
      // set our RGB value from above to be transparent and merge the images with the specified opacity
      if($this->wm_x_transp>0 AND $this->wm_y_transp>0)
      {
        imagecolortransparent($wm_img, imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp));
      }
      imagecopymerge($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height, $this->wm_opacity);
    }

    //  Output the image
    if ($this->dynamic_output == TRUE)
    {
      $this->image_display_gd($src_img);
    }
    else
    {
      if ( ! $this->image_save_gd($src_img))
      {
        return FALSE;
      }
    }

    imagedestroy($src_img);
    imagedestroy($wm_img);

    return TRUE;
  }


}

/* End of file MY_Image_lib.php */
/* Location: ./application/libraries/MY_Image_lib.php */
