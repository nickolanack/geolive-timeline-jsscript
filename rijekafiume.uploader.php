<script type="text/javascript">

/**
 * Custom upload script that simply adds a confirmation box.
 */


window.addEvent('load',function(){

	var message="I promise: cross my heart and hope to die... that I own or have permission to post this content";

	var form=$('adminForm');
	var submit=$('file-upload-submit');

	var label=new Element('label', {html:message});
	var checkbox=new Element('input',{type:'checkbox'});
	label.appendChild(checkbox);

	var fn=submit.onclick;
	submit.onclick=function(){

		if(!checkbox.checked){

			alert('you must check the box ...');
			return false;
		}else{
			return fn.call(submit);
		}

	}

	var p=new Element('p',{'class':'section-intro', style:"color:crimson;"});
	p.appendChild(label);
	form.appendChild(p);


});


</script>