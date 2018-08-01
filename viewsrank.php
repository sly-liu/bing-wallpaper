<?php
	require('./base.php');
	$GLOBALS['p'] = isset($_GET['p']) ? $_GET['p'] : 1;
	//$api = new Base();
	$pics = $api -> getImageByViewsOrder($p, 35);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="bing,wallpaper,view,rank">
	<meta name="description" content="Bing Wallpaper views ranking">
	<title>Views Rank -- Bing Wallpape Gallery</title>
	<link href="images/favicon.ico" rel="shortcut icon">
	<link rel="stylesheet" href="style.css?v=2">

	<!-- Core CSS file -->
	<link rel="stylesheet" href="PhotoSwipe-master/dist/photoswipe.css"> 

	<!-- Skin CSS file (styling of UI - buttons, caption, etc.)
	     In the folder of skin CSS file there are also:
	     - .png and .svg icons sprite, 
	     - preloader.gif (for browsers that do not support CSS animations) -->
	<link rel="stylesheet" href="PhotoSwipe-master/dist/default-skin/default-skin.css"> 

	<!-- Core JS file -->
	<script src="PhotoSwipe-master/dist/photoswipe.min.js"></script> 

	<!-- UI JS file -->
	<script src="PhotoSwipe-master/dist/photoswipe-ui-default.min.js"></script> 
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
	     (adsbygoogle = window.adsbygoogle || []).push({
	          google_ad_client: "ca-pub-7664794252965039",
	          enable_page_level_ads: true
	     });
	</script>

</head>
<body>
	<header class="flex">
		<h1 class="logo">
			<a href="/">Bing Wallpaper Gallery</a>
		</h1>
		<nav>
			<a class="curr" href="./viewsrank">Views Ranking</a>
			<a class="" href="./about.html">About Us</a>
		</nav>
	</header>
	<section>
	<div class="my-gallery clearfix" itemscope itemtype="http://schema.org/ImageGallery">
	<?php 
    foreach($pics['images'] as $key=>$value){
        $tumburl = str_replace('1920x1080', '576x324', $value['url']);
        $shortname = trim(strrchr($value['urlbase'], '/'),'/');
        $pos = strpos($shortname, '_');
        $name = substr($shortname, 0, $pos);
        echo '<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
            <a href="'.$value['url'].'" itemprop="contentUrl" data-size="1920x1080">
            	<img src="'.$tumburl.'" itemprop="thumbnail" alt="'.$value['caption'].'" />
           		<div>
            		<time class="date">'.$value['date'].'</time>
            		<span><span class="icon view">view</span>'.$value['counter'].'</span>
            	</div>
            </a>
            <figcaption itemprop="caption description">'.$value['caption'].'</figcaption>
        </figure>';
    }
    ?>
	</div>
    </section>
    <div class="pagination">
    	<?php 
		for ($x=1; $x<=$pics['total'] ; $x++) {
			if ($p == $x) {
				echo '<a href="/viewsrank?p='.$x.'" class="curr">'.$x.'</a>';
			}else{
				echo '<a href="/viewsrank?p='.$x.'" >'.$x.'</a>';
			}
		} 
		?>
    </div>
    <footer>
    	<p>All the resources of the site comes from <a href="https://www.bing.com">Bing</a></p>
    	<p>Copyright © 2018 - 2019 <a href="https://www.gifposter.com/">Gifposter</a></p>
    </footer>
    
	<!-- Root element of PhotoSwipe. Must have class pswp. -->
	<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

	    <!-- Background of PhotoSwipe. 
	         It's a separate element as animating opacity is faster than rgba(). -->
	    <div class="pswp__bg"></div>

	    <!-- Slides wrapper with overflow:hidden. -->
	    <div class="pswp__scroll-wrap">

	        <!-- Container that holds slides. 
	            PhotoSwipe keeps only 3 of them in the DOM to save memory.
	            Don't modify these 3 pswp__item elements, data is added later on. -->
	        <div class="pswp__container">
	            <div class="pswp__item"></div>
	            <div class="pswp__item"></div>
	            <div class="pswp__item"></div>
	        </div>

	        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
	        <div class="pswp__ui pswp__ui--hidden">

	            <div class="pswp__top-bar">

	                <!--  Controls are self-explanatory. Order can be changed. -->

	                <div class="pswp__counter"></div>

	                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

	                <button class="pswp__button pswp__button--share" title="Share"></button>

	                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

	                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

	                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
	                <!-- element will get class pswp__preloader--active when preloader is running -->
	                <div class="pswp__preloader">
	                    <div class="pswp__preloader__icn">
	                      <div class="pswp__preloader__cut">
	                        <div class="pswp__preloader__donut"></div>
	                      </div>
	                    </div>
	                </div>
	            </div>

	            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
	                <div class="pswp__share-tooltip"></div> 
	            </div>

	            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
	            </button>

	            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
	            </button>

	            <div class="pswp__caption">
	                <div class="pswp__caption__center"></div>
	            </div>

	        </div>

	    </div>

	</div>

	<script>
		var initPhotoSwipeFromDOM = function(gallerySelector) {

		    // parse slide data (url, title, size ...) from DOM elements 
		    // (children of gallerySelector)
		    var parseThumbnailElements = function(el) {
		        var thumbElements = el.childNodes,
		            numNodes = thumbElements.length,
		            items = [],
		            figureEl,
		            linkEl,
		            size,
		            item;

		        for(var i = 0; i < numNodes; i++) {

		            figureEl = thumbElements[i]; // <figure> element

		            // include only element nodes 
		            if(figureEl.nodeType !== 1) {
		                continue;
		            }

		            linkEl = figureEl.children[0]; // <a> element

		            size = linkEl.getAttribute('data-size').split('x');

		            // create slide object
		            item = {
		                src: linkEl.getAttribute('href'),
		                w: parseInt(size[0], 10),
		                h: parseInt(size[1], 10)
		            };



		            if(figureEl.children.length > 1) {
		                // <figcaption> content
		                item.title = figureEl.children[1].innerHTML; 
		            }

		            if(linkEl.children.length > 0) {
		                // <img> thumbnail element, retrieving thumbnail url
		                item.msrc = linkEl.children[0].getAttribute('src');
		            } 

		            item.el = figureEl; // save link to element for getThumbBoundsFn
		            items.push(item);
		        }

		        return items;
		    };

		    // find nearest parent element
		    var closest = function closest(el, fn) {
		        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
		    };

		    // triggers when user clicks on thumbnail
		    var onThumbnailsClick = function(e) {
		        e = e || window.event;
		        e.preventDefault ? e.preventDefault() : e.returnValue = false;

		        var eTarget = e.target || e.srcElement;

		        // find root element of slide
		        var clickedListItem = closest(eTarget, function(el) {
		            return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
		        });

		        if(!clickedListItem) {
		            return;
		        }

		        // find index of clicked item by looping through all child nodes
		        // alternatively, you may define index via data- attribute
		        var clickedGallery = clickedListItem.parentNode,
		            childNodes = clickedListItem.parentNode.childNodes,
		            numChildNodes = childNodes.length,
		            nodeIndex = 0,
		            index;

		        for (var i = 0; i < numChildNodes; i++) {
		            if(childNodes[i].nodeType !== 1) { 
		                continue; 
		            }

		            if(childNodes[i] === clickedListItem) {
		                index = nodeIndex;
		                break;
		            }
		            nodeIndex++;
		        }



		        if(index >= 0) {
		            // open PhotoSwipe if valid index found
		            openPhotoSwipe( index, clickedGallery );
		        }
		        return false;
		    };

		    // parse picture index and gallery index from URL (#&pid=1&gid=2)
		    var photoswipeParseHash = function() {
		        var hash = window.location.hash.substring(1),
		        params = {};

		        if(hash.length < 5) {
		            return params;
		        }

		        var vars = hash.split('&');
		        for (var i = 0; i < vars.length; i++) {
		            if(!vars[i]) {
		                continue;
		            }
		            var pair = vars[i].split('=');  
		            if(pair.length < 2) {
		                continue;
		            }           
		            params[pair[0]] = pair[1];
		        }

		        if(params.gid) {
		            params.gid = parseInt(params.gid, 10);
		        }

		        return params;
		    };

		    var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
		        var pswpElement = document.querySelectorAll('.pswp')[0],
		            gallery,
		            options,
		            items;

		        items = parseThumbnailElements(galleryElement);

		        // define options (if needed)
		        options = {

		            // define gallery index (for URL)
		            galleryUID: galleryElement.getAttribute('data-pswp-uid'),

		            getThumbBoundsFn: function(index) {
		                // See Options -> getThumbBoundsFn section of documentation for more info
		                var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
		                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
		                    rect = thumbnail.getBoundingClientRect(); 

		                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
		            }

		        };

		        // PhotoSwipe opened from URL
		        if(fromURL) {
		            if(options.galleryPIDs) {
		                // parse real index when custom PIDs are used 
		                // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
		                for(var j = 0; j < items.length; j++) {
		                    if(items[j].pid == index) {
		                        options.index = j;
		                        break;
		                    }
		                }
		            } else {
		                // in URL indexes start from 1
		                options.index = parseInt(index, 10) - 1;
		            }
		        } else {
		            options.index = parseInt(index, 10);
		        }

		        // exit if index not found
		        if( isNaN(options.index) ) {
		            return;
		        }

		        if(disableAnimation) {
		            options.showAnimationDuration = 0;
		        }

		        // Pass data to PhotoSwipe and initialize it
		        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		        gallery.init();
		    };

		    // loop through all gallery elements and bind events
		    var galleryElements = document.querySelectorAll( gallerySelector );

		    for(var i = 0, l = galleryElements.length; i < l; i++) {
		        galleryElements[i].setAttribute('data-pswp-uid', i+1);
		        galleryElements[i].onclick = onThumbnailsClick;
		    }

		    // Parse URL and open gallery if it contains #&pid=3&gid=1
		    var hashData = photoswipeParseHash();
		    if(hashData.pid && hashData.gid) {
		        openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
		    }
		};

		// execute above function
		initPhotoSwipeFromDOM('.my-gallery');

	</script>



</body>
</html>