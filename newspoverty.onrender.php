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
     }));
 });

 var eraRange=[(new Date(eras[0].start)).getTime(),(new Date(eras[eras.length-1].end)).getTime()];
 var s=eraRange[0]-range[0];
 var e=eraRange[1]-eraRange[0];
 var r=[dateToPercent(s), dateToPercent(e)];
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

     new UIPopover(pin, {
                anchor:UIPopover.AnchorTo(['top']),
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


 (new TimelineQuery('get_timeline_graph', {showDates:true})).addEvent('success',function(resp){
     var data=resp.values;


     var hintBar=new UIGraph(graphBar, data.map(function(d){return d.count;}), {
                lineTemplate:UIGraph.UnitStepBarsTemplate,
                 //lineTemplate:UIGraph.LineTemplate,
                title:"",
                height:26,
                width:100,
                widthUnit:'%',
                padding:0,
                lineColor: '#CCCCCC',
                fillGradient:true
            });

     var detailBar=new UIGraph(graphBarDetail, data, {
                lineTemplate:UIGraph.UnitStepBarsTemplate,
                 //lineTemplate:UIGraph.LineTemplate,
                title:"Number of changes since 2008",
                height:77,
                width:100,
                widthUnit:'%',
                padding:0,
                lineColor: 'black',
                fillGradient:true,
                highlightTemplate:UIGraph.UnitStepBarsHighlighter,
                parseMeta:function(v,i){
                  return v;
                },
                parseY:function(v,i){
                  return v.count;
                }
            });


     if (resp.subscription) {
          AjaxControlQuery.Subscribe(resp.subscription, function(result) {
              (new TimelineQuery('get_timeline_graph', {showDates:true})).addEvent('success',function(resp){
                data=resp.values;
                hintBar.setData(data.map(function(d){return d.count;}));
                detailBar.setData(data);

              }).execute();
          });
     }


     detailBar.addEvent('click',function(data){

         console.log(data);
         timeline.setDateRange(data.meta.start, data.meta.end);
     });

     //adding a custom popover that follows the cursor.
     var popover=null;
     detailBar.addEvent('mouseover',function(d){

           if(d.y>0){
               if(!popover){
                    popover=new UIPopover(detailBar.canvas);
                    popover.show();
               }

               popover.setText(d.y+" outlet"+(d.y==1?"":"s"));
           }else{
               if(popover){
                    popover.hide();
                    popover.detach();
                    popover=null;

               }

           }

     });




     /*
      * add title and checkboxes to filter the graph
      */
     detailBar.titleEl.addClass('dropdown-menu');
     detailBar.titleEl.appendChild((function(){

                var span=new Element('span', {'class':'timeline-opts'});

                var checkboxes=[];

                var allBoxesAreChecked=function(){
                   for(var i=0;i<checkboxes.length;i++){
                        if(!checkboxes[i].checked){
                            return false;
                        }
                   }
                   return true;
                };
                var someBoxedAreChecked=function(){
                     for(var i=0;i<checkboxes.length;i++){
                            if(checkboxes[i].checked){
                                return true;
                            }
                    }
                    return false;

                };
                var allCheckedOptions=function(){
                    var options=[];
                    for(var i=0;i<checkboxes.length;i++){
                        if(checkboxes[i].checked){
                            options.push(checkboxes[i].getAttribute('data-filter'));
                            }
                   }
                   return options;

                };

                var cacheData={};

                var makeCacheKey=function(){
                    var key= allCheckedOptions().join('-').replace(/[;\s]/g,'');
                    console.log(key);
                    return key;
                };

              (new AjaxControlQuery(CoreAjaxUrlRoot,'distinct_attribute_value_list', {
                    plugin:'Attributes',
                    table:'newsAttributes',
                    field:'transitionType'
                })).cache({
                                     expire:5*3600
                                }).addEvent('success',function(resp){

                        console.log(resp);
                        var options=resp.values;


                var countCbx=0;
                span.appendChild(new Element('br'));
                Array.each(options,function(opt){

                  if((typeof opt)!='string'||opt.length==0){
                    return; //continue;
                  }

                  countCbx++;
                  var checkbox=new Element('input',{type:'checkbox', checked:true, 'data-filter':opt});
                  var label=new Element('label', {title:opt});
                  span.appendChild(label);
                  if(countCbx%3==0){
                    span.appendChild(new Element('br'));
                  }
                  label.appendChild(checkbox);
                  label.appendChild(new Element('span',{html:opt}));
                  checkboxes.push(checkbox);
                  checkbox.addEvent('click', function(){


                        var cacheKey=makeCacheKey();
                        if(allBoxesAreChecked()){

                            detailBar.setData(data);
                            hintBar.setData(data.map(function(d){return d.count;}));

                        }else if((typeof cacheData[cacheKey])!='undefined'){

                                console.log('used cached data');
                                detailBar.setData(cacheData[cacheKey]);
                                hintBar.setData(cacheData[cacheKey].map(function(d){return d.count;}));

                        }else {

                           if(someBoxedAreChecked()){

                            (new TimelineQuery('get_timeline_graph', {
                                showDates:true,
                                filter:AttributeFilter.JoinFilter('newsAttributes', allCheckedOptions().map(function(opt){

                                                    return {
                                                        field:'transitionType',
                                                        comparator:'equalTo',
                                                        value:opt,
                                                    }

                                                }))

                                })).addEvent('success',function(resp){

                                    cacheData[cacheKey]=resp.values;
                                    detailBar.setData(resp.values.map(function(d){return d.count;}));
                                    hintBar.setData(resp.values.map(function(d){return d.count;}));

                            }).execute();

                           }else{

                              //do nothing!
                              detailBar.setData([]);
                              hintBar.setData([]);

                           }

                       }

                  });

                });

        }).execute();

        return span;

    })());





 }).execute();
</script>