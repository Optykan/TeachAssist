// (function(){

	var MarkHandler={
		marks: [],
		report: {},
		table: {},
		classes: ['cat-ku', 'cat-ti', 'cat-comm', 'cat-app', 'cat-final'],
		init: function(table){
			this.table=new List(table, {valueNames: [ 'name', 'cat-ku', 'cat-ti', 'cat-comm', 'cat-app', 'cat-final', 'weight-ku', 'weight-ti', 'weight-comm', 'weight-app', 'weight-final']});
		},
		load: function(jsonSource){
			this.marks=$.parseJSON(jsonSource.html());
			// var that=this;
			// for (var i = this.marks.length - 1; i >= 0; i--) {
			// 	var toAdd = {
			// 		'name':that.marks[i].name,
			// 		'cat-ku':that.marks[i].marks[0],
			// 		'cat-ti':that.marks[i].marks[1],
			// 		'cat-comm':that.marks[i].marks[2],
			// 		'cat-app':that.marks[i].marks[3],
			// 		'cat-final':that.marks[i].marks[4],
			// 		'weight-ku':that.marks[i].weight[0],
			// 		'weight-ti':that.marks[i].weight[1],
			// 		'weight-comm':that.marks[i].weight[2],
			// 		'weight-app':that.marks[i].weight[3],
			// 		'weight-final':that.marks[i].weight[4]
			// 	};
			// 	this.table.add(toAdd);
			// 	console.log(toAdd);
			//}
			// this.table.sort('name', { order: 'asc'});
		}

	};
	MarkHandler.init('assignments');
	MarkHandler.load($('#markData'));

	// var assignments=new List('assignments', {valueNames: [ 'name', 'ku', 'ti', 'comm', 'app', 'final', 'weight-ku', 'weight-ti', 'weight-comm', 'weight-app', 'weight-final']});
	// assignments.sort('name', { order: 'asc'});
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
	new Chartist.Line('#trends', assignmentData, {high:100, low:0, lineSmooth: Chartist.Interpolation.cardinal({ fillHoles: true })});
// })();