function openCat(num)
{
	var type = $("#stype"+num);
	var img = $("#timg"+num);

	if (type.css("display")==("block") )
	{
		type.slideUp(300);
		img.css("transform","scale(1,-1)");

	} else {

		type.slideDown(300),
		img.css("transform","scale(-1,1)");
	}
};
