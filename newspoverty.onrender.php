<script type="text/javascript">
/**
 * Copy the following into Timeline onRender script input (do not include the <script> tags...)
 */


var erraSpan=container.appendChild(new Element('span', {'class':'era-s'}));
var barBack=erraSpan.appendChild(new Element('div', {'class':'era-bar bk'}));
var graphBar=erraSpan.appendChild(new Element('div',{'class':'timeline-graph'}))
var bar=erraSpan.appendChild(new Element('div', {'class':'era-bar'}));
var graphBarDetail=erraSpan.appendChild(new Element('div',{'class':'timeline-graph detail'}))

var eras=[
{start:'2008', end:'2015', label:''}
];
var dateToPercent=function(time){
   return Math.round((time/span)*100.0);
}
Array.each(eras, function(era){

    var eraRange=[(new Date(era.start)).getTime(),(new Date(era.end)).getTime()];

    var s=eraRange[0]-range[0];
    var e=eraRange[1]-eraRange[0];
    var r=[dateToPercent(s), dateToPercent(e)]
    bar.appendChild(new Element('div', {
         'class':'era e-'+era.start,
         'data-label':era.label,
         styles:{
            left:r[0]+'%',
            width:r[1]+'%'
        }
    })).addEvent('click',function(){
        timeline.setValue([r[0], r[0]+r[1]]);
    });
});

var eraRange=[(new Date(eras[0].start)).getTime(),(new Date(eras[eras.length-1].end)).getTime()];
var s=eraRange[0]-range[0];
var e=eraRange[1]-eraRange[0];
var r=[dateToPercent(s), dateToPercent(e)]
barBack.appendChild(new Element('div', {
         styles:{
            left:r[0]+'%',
            width:r[1]+'%'
        }
}));


var eventsBar=container.appendChild(new Element('div', {'class':'events-bar'}));


//event class: a, b, c, and d are used to alter the height and label directions

var events=[
//{start:'1804', label:'under Austrian control'},

];

Array.each(events, function(event){

    var startTime=(new Date(event.start)).getTime();

    var startOffset=startTime-range[0];
    var startPercent=dateToPercent(startOffset);
    var pin =eventsBar.appendChild(new Element('div', {
         'class':'e-'+event.start+' '+(event.class||'a'),
         'data-label':event.start,
         styles:{
            left:startPercent+'%',
        }
    })).addEvent('click',function(){

    });

    new UIPopover(pin, {anchor:UIPopover.AnchorTo(['top']),
				title:'',
				description:event.label//,
				//hideDelay:500,
				//margin:50
			});

});


//bar graph
<?php
Behavior('graph');
?>
(new TimelineQuery('get_timeline_graph', {})).addEvent('success',function(resp){
    var data=resp.values;


new UIGraph(graphBar, data, {
                                lineTemplate:UIGraph.UnitStepTemplate,
                                //lineTemplate:UIGraph.LineTemplate,
				title:"",
				height:26,
				width:900,
                                widthUnit:'%',
				padding:0,
				lineColor: '#CCCCCC',
                                fillColor:'rgba(233,233,233,0.2)'
			});

new UIGraph(graphBarDetail, data, {
                                lineTemplate:UIGraph.UnitStepTemplate,
                                //lineTemplate:UIGraph.LineTemplate,
				title:"Distribution of outlet close dates",
				height:71,
				width:900,
                                widthUnit:'%',
				padding:0,
				lineColor: 'black',
                                fillColor:'rgb(179, 209, 255)'
			});

}).execute();


</script>