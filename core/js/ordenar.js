	  /**
	 // * jQuery.fn.sortElements // http://james.padolsey.com/javascript/sorting-elements-with-jquery/
	 * --------------
	 * @param Function comparator:
	 *   Exactly the same behaviour as [1,2,3].sort(comparator)
	 *
	 * @param Function getSortable
	 *   A function that should return the element that is
	 *   to be sorted. The comparator will run on the
	 *   current collection, but you may want the actual
	 *   resulting sort to occur on a parent or another
	 *   associated element.
	 *
	 *   E.g. $('td').sortElements(comparator, function(){
	 *      return this.parentNode;
	 *   })
	 *
	 *   The <td>'s parent (<tr>) will be sorted instead
	 *   of the <td> itself.
	 */
	jQuery.fn.sortElements = (function(){

		var sort = [].sort;

		return function(comparator, getSortable) {

			getSortable = getSortable || function(){return this;};

			var placements = this.map(function(){

				var sortElement = getSortable.call(this),
					parentNode = sortElement.parentNode,

					// Since the element itself will change position, we have
					// to have some way of storing its original position in
					// the DOM. The easiest way is to have a 'flag' node:
					nextSibling = parentNode.insertBefore(
						document.createTextNode(''),
						sortElement.nextSibling
					);

				return function() {

					if (parentNode === this) {
						throw new Error(
							"You can't sort elements if any one is a descendant of another."
						);
					}

					// Insert before flag:
					parentNode.insertBefore(this, nextSibling);
					// Remove flag:
					parentNode.removeChild(nextSibling);

				};

			});

			return sort.call(this, comparator).each(function(i){
				placements[i].call(getSortable.call(this));
			});

		};

	})();

	//Ordenar
	function ordenar(tipo) {
		var target = $('[onclick="ordenar(\'' + tipo + '\')"]');
		if( target.attr('data-orden')=='ASC' ){

			target.attr('data-orden','DESC');
			target.children('span').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
			$('table.list td[data-type='+tipo+']').sortElements(
				function(a, b){  return $(a).text() > $(b).text() ? 1 : -1;  },
				function(){ return this.parentNode; }
			);
		}else{

			target.attr('data-orden','ASC');
			target.children('span').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
			$('table.list td[data-type='+tipo+']').sortElements(
				function(a, b){  return $(a).text() < $(b).text() ? 1 : -1;  },
				function(){ return this.parentNode; }
			);
		}
		return false;
	};