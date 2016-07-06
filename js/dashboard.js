var Dashboard={
	weights: [0,0,0,0,0],
	achievement: [0,0,0,0,0],
	getWeight: function(a){
		if(a)
			return parseFloat(a.match(/W: ([0-9.]+)/)[1]);
		return 0;
	},
	getMark: function(a){
		if(a){
			var matches=a.match(/([0-9.]+) \/ ([0-9.]+) = [0-9.]+%/);
			return parseFloat(matches[1])/parseFloat(matches[2]);
		}
		return 0;
	},
	calculate: function(){
		var that=this;
		$(".assignment").each(function(){
			that.weights[0]+=that.getWeight($(this).find(".ku .weight").text());
			that.weights[1]+=that.getWeight($(this).find(".ti .weight").text());
			that.weights[2]+=that.getWeight($(this).find(".comm .weight").text());
			that.weights[3]+=that.getWeight($(this).find(".app .weight").text());
			that.weights[4]+=that.getWeight($(this).find(".final .weight").text());
		});
		$(".assignment").each(function(){
			that.achievement[0]+=(that.getWeight($(this).find(".ku .weight").text())/that.weights[0])*that.getMark($(this).find(".mark").text());
			that.achievement[1]+=(that.getWeight($(this).find(".ti .weight").text())/that.weights[1])*that.getMark($(this).find(".mark").text());
			that.achievement[2]+=(that.getWeight($(this).find(".comm .weight").text())/that.weights[2])*that.getMark($(this).find(".mark").text());
			that.achievement[3]+=(that.getWeight($(this).find(".app .weight").text())/that.weights[3])*that.getMark($(this).find(".mark").text());
			that.achievement[4]+=(that.getWeight($(this).find(".final .weight").text())/that.weights[4])*that.getMark($(this).find(".mark").text());
		});
	}
}