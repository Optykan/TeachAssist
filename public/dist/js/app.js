// (function(){
var assignments=new List('assignments', {valueNames: [ 'name', 'ku', 'ti', 'comm', 'app', 'final', 'weight-ku', 'weight-ti', 'weight-comm', 'weight-app', 'weight-final']});
assignments.sort('name', { order: 'asc'});
$('.sort').click(function(){
	$('.sort').each(function(){
		if($(this).hasClass('asc')){
			$(this).find('.icon')[0].className='icon ion-ios-arrow-up';
		}else if($(this).hasClass('desc')){
			$(this).find('.icon')[0].className='icon ion-ios-arrow-down';
		}else{
			$(this).find('.icon')[0].className='icon ion-ios-minus-empty';
		}	
	});
});


new Chartist.Pie('#weighting', pieData);
new Chartist.Line('#trends', assignmentData, {high:100, low:0, lineSmooth: Chartist.Interpolation.cardinal({ fillHoles: true }), plugins: [Chartist.plugins.tooltip()]});
// })();