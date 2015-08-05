<script type="text/javascript">
/**
 * Copy the following into Timeline onRender script input (do not include the <script> tags...)
 */

var dateToPercent=function(time){
    return Math.round((time/span)*100.0);
}


var eventsBar=container.appendChild(new Element('div', {'class':'events-bar'}));




var min=(new Date(range[0])).toISOString().split('T')[0].substring(0,7)+"-01";
var max=(new Date(span+range[0])).toISOString().split('T')[0].substring(0,7)+"-01";

filterManager.search(
	AttributeFilter.InsersectFilter('markerAttributes',
		[
            {
                field:'sessionDate',
                comparator:'greatorThanOrEqualTo',
                value:min,
                format:'date'
            },
            {
                field:'sessionDate',
                comparator:'lessThanOrEqualTo',
                value:max,
                format:'date'
            }
        ]
	),
    {
	    json:{
		    format:['id', 'attribute.*'],
		    show:['matches'],
		    group:[]
	    }
    },
    function(result){
        var events=[];
        Object.each(result.results.matches,function(match){

        	var startTime=(new Date(match.sessionDate)).getTime();

            var startOffset=startTime-range[0];
            var startPercent=dateToPercent(startOffset);
            var startPercent=startPercent%100;
            if(startPercent<0){
                var startPercent=startPercent+100;
            }


            events.push({
                start:match.sessionDate,
                percent:startPercent,
                label:'event',
                onclick:function(){
                    GeoliveSearch.SearchAndOpenMapItem(application, match.id, match.lid);
                    }
                },
                popover:function(p){

                	//going to search for items name, and then updated the display text.

                }
            );
        });

        //event class: a, b, c, and d are used to alter the height and label directions
        for(var i=1;i<events.lengt;i++){
            // process events to ensure height distibution (by adding class name)
            // in case items are very close horizontally.
        }


        Array.each(events, function(event){

            var pin =eventsBar.appendChild(new Element('div', {
                    'class':'e-'+event.start+' '+(event['class']||'a'),
                    'data-label':event.start,
                    styles:{
                        left:event.percent+'%',
                    }
                })).addEvent('click',event.onclick);


            event.popover(new UIPopover(pin, {anchor:UIPopover.AnchorTo(['top']),
                title:'',
                description:event.label//,
                //hideDelay:500,
                //margin:50
                })
            );

        });

    }
);


</script>