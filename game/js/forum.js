window.addEvent('domready', function()
{
	document.getElements('.forum_post').each(function(wrap)
	{
		if ($(wrap).retrieve('ForumPost'))
		{
			return;
		}
		
		wrap.store('ForumPost', new ForumPost(wrap));
	});
});

var ForumPosts = [];
var ForumPost = new Class({
	initialize: function(wrap)
	{
		this.wrap = wrap.set('tween', { duration: 300 });
		this.links = wrap.getElements('.post_top .links');
		this.overlay = this.wrap.getElement('.overlay').set('tween', { duration: 300 }).fade('hide');
		this.post_text = this.wrap.getElement('.topic_text');
		
		this.post_id = this.wrap.get('id').substring(2);
		ForumPosts[this.post_id] = this;
		
		this.type = {
			'short': wrap.hasClass('topic') ? 't' : 'r',
			'long': wrap.hasClass('topic') ? 'topic' : 'reply'
		};
		
		var self = this;

		var forum_delete = this.wrap.getElement('div.post_top div.links a.delete');
		if (forum_delete) { 
			forum_delete.addEvent('click', function()
			{
				if (confirm('Are you sure you want to remove this ' + (self.type.long == 'topic' ? 'thread' : 'answer') + '?'))
				{
					self.post_delete();
				}
				
				return false;
			});
		}
		
		var forum_recreate = this.wrap.getElement('div.post_top div.links a.recreate');
		if (forum_recreate) { 
			forum_recreate.addEvent('click', function()
			{
				if (confirm('Are you sure you want to activate this ' + (self.type.long == 'topic' ? 'thread' : 'answer') + '?'))
				{
					self.post_recreate();
				}
				
				return false;
			});
		}

		var forum_sticky = this.wrap.getElement('div.post_top div.links a.sticky_change');
		if (forum_sticky) { 
			forum_sticky.addEvent('click', function()
			{
				self.post_sticky(this.hasClass('set_sticky') ? true : false);
				
				return false;
			});
		}

		var forum_lock = this.wrap.getElement('div.post_top div.links a.lock_change');
		if (forum_lock) { 
			forum_lock.addEvent('click', function()
			{
				self.post_lock(this.hasClass('lock') ? true : false);
				
				return false;
			});
		}

		var forum_edit = this.wrap.getElement('div.post_top div.links a.edit');
		if (forum_edit) { 
			forum_edit.addEvent('click', function()
			{
				self.post_get_form('edit_form');
				
				return false;
			});
		}

		this.wrap.getElement('ul.menu li.opt_4 a').addEvent('click', function()
		{
			self.post_get_form('report_form');

			return false;
		});
	},
	
	makeOverlay: function(html)
	{
		ForumPosts.each(function(post)
		{
			post.overlay.empty().fade(0);
		});
		
		if (!$chk(html)) html = '';
		
		this.overlay.set('html', html).fade(1);
	},
	
	post_delete: function()
	{
		this.makeOverlay('<p class="center" style="margin-top: 50px;"><img src="images/ajax_load.gif" alt="Loading..." /></p>');
		
		this.request = $empty;
		
		var self = this;
		this.request = new Request({ url: '/game/js/ajax/forum_post_handle.php', data: 'task=delete&type=' + self.type.short + '&id=' + self.post_id, method: 'get' });
		this.request.addEvents(
		{
			success: function(data)
			{
				data = data.split(':');
				
				if (data[0] == 'ERROR')
				{
					self.overlay.set('html', '<h2 class="center">This answer couldn\'t be deleted!</h2>');
					(function(){ self.overlay.fade(0); }).delay(3000);
				}
				else
				{
					if (self.type.long == 'topic')
					{
						NavigateTo('/game/?side=forum/forum&f=' + data[2]);
					}
					else
					{
						self.wrap.fade(0);
						(function(){ self.wrap.destroy(); }).delay(300);
					}
				}
			},
			failure: function(data)
			{
				self.overlay.set('html', '<h2 class="center">Unknown error!</h2>');
				(function(){ self.overlay.fade(0); }).delay(3000);
			}
		});
		this.request.send();
	},
	
	post_recreate: function()
	{
		this.makeOverlay('<p class="center" style="margin-top: 50px;"><img src="images/ajax_load.gif" alt="Loading..." /></p>');
		
		this.request = $empty;
		
		var self = this;
		this.request = new Request({ url: '/game/js/ajax/forum_post_handle.php', data: 'task=recreate&type=' + self.type.short + '&id=' + self.post_id, method: 'get' });
		this.request.addEvents(
		{
			success: function(data)
			{
				data = data.split(':');
				
				if (data[0] == 'ERROR')
				{
					self.overlay.set('html', '<h2 class="center">Unknown error!</h2>');
					(function(){ self.overlay.fade(0); }).delay(3000);
				}
				else
				{
					self.overlay.set('html', '<h2 class="center">This thread was opened!</h2>');
					(function(){ self.overlay.fade(0); }).delay(3000);
				}
			},
			failure: function(data)
			{
				self.overlay.set('html', '<h2 class="center">Unknown error!</h2>');
				(function(){ self.overlay.fade(0); }).delay(3000);
			}
		});
		this.request.send();
	},
	
	post_sticky: function(state)
	{
		this.makeOverlay('<p class="center" style="margin-top: 50px;"><img src="images/ajax_load.gif" alt="" /></p>');
		
		this.request = $empty;
		
		var self = this;
		this.request = new Request({ url: '/game/js/ajax/forum_post_handle.php', data: 'task=' + (state === true ? 'set_sticky' : 'undo_sticky') + '&type=' + self.type.short + '&id=' + self.post_id, method: 'get' });
		this.request.addEvents(
		{
			success: function(data)
			{
				data = data.split(':');
				
				if (data[0] == 'ERROR')
				{
					self.overlay.set('html', '<h2 class="center">Unknown error!</h2>');
					(function(){ self.overlay.fade(0); }).delay(3000);
				}
				else
				{
					self.overlay.set('html', '<h2 class="center">You have set ' + (state === true ? 'this thread as STICKY!' : 'this threas as normal!') + '.</h2>');
					(function(){ self.overlay.fade(0); }).delay(3000);
				}
			},
			failure: function(data)
			{
				self.overlay.set('html', '<h2 class="center">Unknown error!</h2>');
				(function(){ self.overlay.fade(0); }).delay(3000);
			}
		});
		this.request.send();
	},
	
	post_lock: function(state)
	{
		this.makeOverlay('<p class="center" style="margin-top: 50px;"><img src="images/ajax_load.gif" alt="Please wait..." /></p>');
		
		this.request = $empty;
		
		var self = this;
		this.request = new Request({ url: '/game/js/ajax/forum_post_handle.php', data: 'task=' + (state === true ? 'lock' : 'unlock') + '&type=' + self.type.short + '&id=' + self.post_id, method: 'get' });
		this.request.addEvents(
		{
			success: function(data)
			{
				data = data.split(':');
				
				if (data[0] == 'ERROR')
				{
					self.overlay.set('html', '<h2 class="center">Unknown error!</h2>');
					(function(){ self.overlay.fade(0); }).delay(3000);
				}
				else
				{
					self.overlay.set('html', '<h2 class="center">You have ' + (state === true ? 'closed' : 'opened') + ' this thread.</h2>');
					(function(){ self.overlay.fade(0); }).delay(3000);
				}
			},
			failure: function(data)
			{
				self.overlay.set('html', '<h2 class="center">Unknown error!</h2>');
				(function(){ self.overlay.fade(0); }).delay(3000);
			}
		});
		this.request.send();
	},
	
	post_get_form: function(task)
	{
		this.makeOverlay('<p class="center" style="margin-top: 50px;"><img src="images/ajax_load.gif" alt="Please wait..." /></p>');
		
		this.request = $empty;
		
		var self = this;
		this.request = new Request({ url: '/game/js/ajax/forum_post_handle.php', data: 'task=' + task + '&type=' + self.type.short + '&id=' + self.post_id, method: 'get' });
		this.request.addEvents(
		{
			success: function(data)
			{
				data = data.split(':');
				
				if (data[0] == 'ERROR')
				{
					self.overlay.set('html', '<h2 class="center">Unknown error!</h2>');
					(function(){ self.overlay.fade(0); }).delay(3000);
				}
				else
				{
					self.overlay.set('html', data.join(':'));
					self.post_form_handle(task);
				}
			},
			failure: function(data)
			{
				self.overlay.set('html', '<h2 class="center">Unknown error!</h2>');
				(function(){ self.overlay.fade(0); }).delay(3000);
			}
		});
		this.request.send();
	},
	
	post_form_handle: function(task)
	{
		check_html(this.overlay);
		
		var form = this.overlay.getElement('form');
		var send_result = form.getElement('#send_result');
		var self = this;
		
		var textarea = form.getElement('textarea');
		var offset = this.type.long == 'topic' ? 200 : 120;
		var height = this.overlay.getSize().y;
		height = height > 600 ? 600 - offset : height - offset;
		
		textarea.setStyle('height', height + 'px');
		
		form.getElement('input.cancel').addEvent('click', function()
		{
			self.overlay.empty().fade(0);
			
			return false;
		});
		
		form.addEvent('submit', function(e)
		{
			new Event(e).stop();
			
			send_result.set('html', '<img src="/game/images/ajax_load_small.gif" alt="" />');
			
			self.request = $empty;
			
			self.request = new Request.JSON({ url: this.get('action'), method: 'post', data: this.toQueryString() });
			self.request.addEvents(
			{
				success: function(data)
				{
					if (task == 'edit_form')
					{
						if ($chk(data.error))
						{
							send_result.set('html', 'Unknown error!');
						}
						else
						{
							self.post_text.set('html', data.text);
							
							check_html(self.post_text);
							
							self.overlay.set('html', '<h2 class="center">Successfully changed</h2>');
							(function(){ self.overlay.fade(0); }).delay(3000);
						}
					}
					else
					{
						if ($chk(data.error))
						{
							send_result.set('html', data.error);
							detect_counters(send_result);
						}
						else
						{
							self.overlay.set('html', '<h2 class="center">Reported with success, thank you!</h2>');
							(function(){ self.overlay.fade(0); }).delay(3000);
						}
					}
				},
				failure: function(data)
				{
					send_result.set('html', 'Unknown error!');
				}
			});
			self.request.send();
		});
	}
});