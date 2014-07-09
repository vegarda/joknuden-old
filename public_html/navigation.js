$(document).ready(function(){
        //console.log(window.location.pathname)
		$('li.active').removeClass('active');
		console.log(window.location.pathname);
		$("a[href=\""+window.location.pathname+"\"]").parent().addClass('active');
  });