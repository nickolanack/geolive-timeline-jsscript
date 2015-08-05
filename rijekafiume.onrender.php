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
{start:'1815', end:'1914', label:'habsburg era'},
{start:'1914', end:'1924', label:'WWI'},
{start:'1924', end:'1941', label:'italian fiume'},
{start:'1941', end:'1947', label:'WWII'},
{start:'1947', end:'1990', label:'yugoslav rijeka'},
{start:'1990', end:'2015', label:'croatian rijeka'}

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
{start:'1804', label:'under Austrian control'},
{start:'1822', label:'under Hungarian control'},
{start:'1848', end:'1867', label:'under Croation control'},
{start:'1868', label:'Hungarian-Croatian compromise'},
{start:'1919', label:'treaty of St. Germain'},
{start:'1920', label:'treaty of Rapallo', 'class':'b'},
{start:'1924', label:'treaty of rome', 'class':'c'},
{start:'April 1941', label:'Italian occupation of Susak'},
{start:'1943', label:'surrender to Allies followed by inclusion in German Adriatic Littoral Zone', 'class':'b'},
{start:'May 1945', label:'Yugoslav capture of Rijeka', 'class':'c'},
{start:'1947', label:'Paris peace treaty', 'class':'d'},
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
		title:"",
		height:71,
		width:900,
		widthUnit:'%',
		padding:0,
		lineColor: 'black',
		fillColor:'rgb(179, 209, 255)'
	});

}).execute();


</script>