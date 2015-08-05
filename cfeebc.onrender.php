<script type="text/javascript">
/**
 * Copy the following into Timeline onRender script input (do not include the <script> tags...)
 */

var dateToPercent=function(time){
    return Math.round((time/span)*100.0);
}


var eventsBar=container.appendChild(new Element('div', {'class':'events-bar'}));


//event class: a, b, c, and d are used to alter the height and label directions

var min=(new Date(range[0])).toISOString().split('T')[0].substring(0,7)+"-01";
var max=(new Date(span+range[0])).toISOString().split('T')[0].substring(0,7)+"-01";

filterManager.search(AttributeFilter.InsersectFilter('markerAttributes',[
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
]),
{json:{format:['id', 'attribute.*'], show:['matches'], group:[]}},function(result){
    var events=[];
    Object.each(result.results.matches,function(match){
        events.push({start:match.sessionDate, label:'event', onclick:function(){
            GeoliveSearch.SearchAndOpenMapItem(application, match.id, match.lid);
        }
        });
    });

        Array.each(events, function(event){

            var startTime=(new Date(event.start)).getTime();

            var startOffset=startTime-range[0];
            var startPercent=dateToPercent(startOffset);
            var startPercent=startPercent%100;
            if(startPercent<0){
                var startPercent=startPercent+100;
            }
            var pin =eventsBar.appendChild(new Element('div', {
                'class':'e-'+event.start+' '+(event.class||'a'),
                'data-label':event.start,
                styles:{
                left:startPercent+'%',
            }
            })).addEvent('click',event.onclick);

            new UIPopover(pin, {anchor:UIPopover.AnchorTo(['top']),
            title:'',
            description:event.label//,
            //hideDelay:500,
            //margin:50
            });

        });

});


</script>