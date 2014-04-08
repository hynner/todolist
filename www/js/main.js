$(function(){
	$(".tagbox").each(function(){
		$(this).tagit({
			fieldName: $(this).attr("name") + "[]"
		});
	});
});
