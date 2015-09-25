<script type="text/javascript">
/**
 * Copy the following into Timeline onRender script input (do not include the <script> tags...)
 */


var erraSpan=container.appendChild(new Element('span', {'class':'era-s'}));
var barBack=erraSpan.appendChild(new Element('div', {'class':'era-bar bk'}));
var graphBar=erraSpan.appendChild(new Element('div',{'class':'timeline-graph'}))
var bar=erraSpan.appendChild(new Element('div', {'class':'era-bar'}));
var graphBarDetail=erraSpan.appendChild(new Element('div',{'class':'timeline-graph detail'}))


var dateToPercent=function(time){
   return Math.round((time/span)*100.0);
}




var eventsBar=container.appendChild(new Element('div', {'class':'events-bar'}));





//bar graph
<?php
Behavior('graph');
?>
(new TimelineQuery('get_timeline_graph', {})).addEvent('success',function(resp){
    var data=resp.values;


    new UIGraph(graphBar, data, {
                lineTemplate:UIGraph.UnitStepBarsTemplate,
                //lineTemplate:UIGraph.LineTemplate,
				title:"",
				height:26,
				width:900,
                widthUnit:'%',
				padding:0,
				lineColor: '#CCCCCC',
				fillGradient:true,
				fillGradientArray:[

				                   'rgba(0, 255, 255, 0.7)',
				                   'rgba(100, 149, 237, 0.7)'
				                   ]
			});

    /*
    new UIGraph(graphBarDetail, data, {
                lineTemplate:UIGraph.UnitStepBarsTemplate,
                //lineTemplate:UIGraph.LineTemplate,
				title:"Number of projects in within timespan",
				height:77,
				width:900,
                widthUnit:'%',
				padding:0,
				lineColor: 'black',
                fillGradient:true,
                fillGradientArray:[

				                   'rgba(0, 255, 255, 0.7)',
				                   'rgba(100, 149, 237, 0.7)'
				                   ]

			});
    */
}).execute();


</script>