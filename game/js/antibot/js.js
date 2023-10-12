/*
	JavaScript for nmafia.unterweltmafia.de
*/

var AntibotForm = new Class({
	initialize: function(form, side, location_href)
	{
		var content = new Element('div',
		{
			'class': 'bg_c',
			html: '<h1>' + c_date + '</h1>'
		}).inject(form);
		
		this.request = new Request.JSON({ url: '/game/js/antibot/antibot_data.php', method: 'get', data: 'script=' + side });
		this.request.addEvents(
		{
			'success': function(data)
			{
				if ($type(data.error) == 'string')
				{
					content.set('html', '<h1>' + error + '</h1><p>' + data.error + '</p>');
				}
				else
				{
					content.set('html', '<h1 class="center">' + image + ' <span style="color: #999999; font-weight: bold; font-size: 14px;">' + data.text + '</span>.</h1><div class="hr big" style="margin: 10px 0 10px 0;"></div>').addClass('antibotform');
					
					var content_wrap = new Element('div', { 'class': 'wrap' }).inject(content);
					var content_wrap_result = new Element('div', { 'class': 'clickResult', html: image + ' <b>' + data.text + '</b>.' }).inject(content_wrap);
					var content_images_wrap = new Element('div').inject(content_wrap);
					
					new Element('div', { 'class': 'clear' }).inject(content_wrap);
					
					data.images.each(function(item)
					{
						new Element('a',
						{
							'class': 'image',
							html: '<img src="/game/js/antibot/antibot_image.php?script=' + side + '&amp;hash=' + item.hash + '" alt="" />'
						}).addEvent('click', function()
						{
							content_wrap_result.set('html', '' + check + '');
							
							var xhr = new Request({ url: '/game/js/antibot/antibot_try.php', method: 'get', data: 'script=' + side + '&hash=' + item.hash });
							xhr.addEvents(
							{
								'success': function(result)
								{
									var res = result.split(':');
									
									if (res[0] == 'REDIRECT')
									{
										document.location = location_href;
									}
									else
									{
										content_wrap_result.set('html', result);
										detect_counters(content_wrap_result);
									}
								},
								'failure': function(result)
								{
									content_wrap_result.set('html', 'ERROR');
								}
							});
							xhr.send();
						}).inject(content_images_wrap);
					});
				}
			},
			'failure': function(data)
			{
				content.set('html', '<h1>' + d_error + '</h1><p>' + data + '</p>');
			}
		});
		
		this.request.send();
	}
});