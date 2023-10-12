var previews={1:1,2:2,3:3,4:4,5:5,6:6,7:7,8:8,9:9,10:10,11:11}
window.addEvents({'domready':function()
{var image=new Element('img').set('tween',{duration:200}).fade('hide').inject($('preview'));var setImage=function(imgID)
{var img=previews[imgID];if($chk(img))
{image.fade(0);(function()
{image.set('src','../img/preview_'+imgID+'.jpg');image.fade(1);}).delay(200);}}
var currentImg=$random(1,11);setImage(currentImg);document.getElements('#preview .nav a').each(function(elem)
{elem.addEvent('click',function()
{var newCurrent=currentImg;if(elem.hasClass('next'))
{currentImg++;}
else
{if(currentImg>1)
currentImg--;}
if(!$chk(previews[currentImg]))
currentImg=1;if(newCurrent!=currentImg)
setImage(currentImg);return false;});});},'load':function()
{(function()
{for(imgID in previews)
{new Asset.image('img/preview_'+imgID+'.jpg');}}).delay(2000);}});