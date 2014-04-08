$(function(){
	$(".tagbox").each(function(){
		var availTags = [];
		$("#" + $(this).attr("id") + "-avail span").each(function () {
			availTags.push($(this).text());
		});
		console.log(availTags);
		$(this).tagit({
			availableTags: availTags,
			fieldName: $(this).attr("name") + "[]"
		});
	});
});
