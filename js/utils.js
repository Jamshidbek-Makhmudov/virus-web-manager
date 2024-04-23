'use strict';

/*
window.chartColors = {
	red: 'rgba(255, 99, 132,1)',
	orange: 'rgba(255, 159, 64,1)',
	yellow: 'rgba(255, 205, 86,1)',
	green: 'rgba(75, 192, 192,1)',
	blue: 'rgba(54, 162, 235,1)',
	purple: 'rgba(153, 102, 255,1)',
	grey: 'rgba(201, 203, 207,1)'
};
*/

window.chartColors = [
	'rgba(255, 99, 132,1)',
	'rgba(255, 159, 64,1)',
	'rgba(255, 205, 86,1)',
	'rgba(75, 192, 192,1)',
	'rgba(54, 162, 235,1)',
	'rgba(153, 102, 255,1)',
	'rgba(201, 203, 207,1)'
];

(function(global) {

	if(lang_code == "CN") {
		var Months =['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'];
		var WEEKS = ['星期天','星期一','星期二','星期三','星期四','星期五','星期六'];

	}else if(lang_code =="JP"){
		var Months =['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'];
		var WEEKS = ['星期天','星期一','星期二','星期三','星期四','星期五','星期六'];

	}else if(lang_code =="EN"){
		var Months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
		var WEEKS = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

	}else{
		var Months = ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'];
		var WEEKS = ['일요일','월요일','화요일','수요일','목요일','금요일','토요일'];
	}

	var COLORS = [
		'#4dc9f6',
		'#f67019',
		'#f53794',
		'#537bc4',
		'#acc236',
		'#166a8f',
		'#00a950',
		'#58595b',
		'#8549ba'
	];

	var Samples = global.Samples || (global.Samples = {});
	var Color = global.Color;

	Samples.utils = {
		// Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
		srand: function(seed) {
			this._seed = seed;
		},

		rand: function(min, max) {
			var seed = this._seed;
			min = min === undefined ? 0 : min;
			max = max === undefined ? 1 : max;
			this._seed = (seed * 9301 + 49297) % 233280;
			return min + (this._seed / 233280) * (max - min);
		},

		numbers: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 1;
			var from = cfg.from || [];
			var count = cfg.count || 8;
			var decimals = cfg.decimals || 8;
			var continuity = cfg.continuity || 1;
			var dfactor = Math.pow(10, decimals) || 0;
			var data = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = (from[i] || 0) + this.rand(min, max);
				if (this.rand() <= continuity) {
					data.push(Math.round(dfactor * value) / dfactor);
				} else {
					data.push(null);
				}
			}

			return data;
		},

		labels: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 100;
			var count = cfg.count || 8;
			var step = (max - min) / count;
			var decimals = cfg.decimals || 8;
			var dfactor = Math.pow(10, decimals) || 0;
			var prefix = cfg.prefix || '';
			var values = [];
			var i;

			for (i = min; i < max; i += step) {
				values.push(prefix + Math.round(dfactor * i) / dfactor);
			}

			return values;
		},

		months: function(config) {
			var cfg = config || {};
			var count = cfg.count || 12;
			var section = cfg.section;
			var values = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = Months[Math.ceil(i) % 12];
				values.push(value.substring(0, section));
			}

			return values;
		},

		weeks: function(config) {
			var cfg = config || {};
			var count = cfg.count || 7;
			var section = cfg.section;
			var values = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = WEEKS[Math.ceil(i) % 7];
				values.push(value.substring(0, section));
			}

			return values;
		},

		color: function(index) {
			return COLORS[index % COLORS.length];
		},

		transparentize: function(color, opacity) {
			var alpha = opacity === undefined ? 0.5 : 1 - opacity;
			return Color(color).alpha(alpha).rgbString();
		}
	};

	// DEPRECATED
	window.randomScalingFactor = function() {
		return Math.round(Samples.utils.rand(-100, 100));
	};

	// INITIALIZATION

	Samples.utils.srand(Date.now());

	// Google Analytics
	/* eslint-disable */
	if (document.location.hostname.match(/^(www\.)?chartjs\.org$/)) {
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-28909194-3', 'auto');
		ga('send', 'pageview');
	}
	/* eslint-enable */

}(this));