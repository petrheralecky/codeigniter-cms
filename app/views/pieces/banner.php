
<link rel="stylesheet" type="text/css" href="<?=PATH?>css/rotator.css" />
<script type="text/javascript" src="<?=PATH?>js/rotator.js"></script>
<script type="text/javascript">
    	$(document).ready(
			function() {
				$(".container").wtRotator({
					width:516,
					height:300,
					button_width:24,
					button_height:24,
					button_margin:5,
					auto_start:true,
					delay:6000,
					transition:"random",
					transition_speed:800,
					cpanel_align:"BR",
					display_thumbs:true,
					display_dbuttons:false,
					display_playbutton:false,
					display_tooltip:true,
					display_numbers:true,
					display_timer:false,
					mouseover_pause:false,
					cpanel_mouseover:false,
					text_mouseover:false,
					text_effect:"down",
					shuffle:false,
					block_size:75,
					vert_size:55,
					horz_size:50,
					block_delay:25,
					vstripe_delay:75,
					hstripe_delay:150
				});
			}
		);
    </script>

<div class="container">
	<div class="wt-rotator">
        <a href="#"></a>
        <div class="desc"></div>
        <div class="preloader"></div>
        <div class="c-panel">
      		<div class="buttons">
            	<div class="prev-btn"></div>
                <div class="play-btn"></div>
            	<div class="next-btn"></div>
            </div>
            <div class="thumbnails">
                <ul>
                    <li effect="fade">
                    	<a href="<?=PATH?>rotator/i00.jpg" title=""></a>
                    </li>
					<li>
                    	<a href="<?=PATH?>rotator/i03.jpg" title=""></a>
                    </li>
					 <li>
                    	<a href="<?=PATH?>rotator/i04.jpg" title=""></a>
                    </li>
					 <li>
                    	<a href="<?=PATH?>rotator/i01.jpg" title=""></a>
                    </li>
					 <li>
                    	<a href="<?=PATH?>rotator/i02.jpg" title=""></a>
                    </li>
                    <!--<li>
	                   	<a href="<?=PATH?>rotator/3.jpg" title="architecture"></a>
                        <a href="<?=BASE?>produkt/progress_tajga_/3" ></a>
                        <p style="top:4px; left:4px; width:306px; height:auto">
                            <span class="title">JQuery Banner Rotator</span><br/>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Sed sed elit eget purus consequat tempor eu ac mauris. Nulla facilisi.
                            Vivamus consectetur molestie ipsum ac sollicitudin.
                            Sed venenatis est sit amet nibh molestie vel pharetra velit commodo.
                            Ut eros orci, sollicitudin sit amet ultricies vitae, varius ac quam. Pellentesque euismod.
                       	</p>
                  	</li>
                   	<li>
                        <a href="<?=PATH?>rotator/1.jpg" title="architecture"></a>
                        <a href="<?=BASE?>produkt/progress_tajga_/3" ></a>
                       	<p style="top:78px; left:150px; width:306px; height:auto">
                            <span class="title">JQuery Banner Rotator</span><br/>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Ut tincidunt mi a lectus gravida pulvinar. Aliquam viverra cursus felis,
                            lobortis elementum tortor malesuada non. Suspendisse hendrerit tortor a mauris auctor eu sodales metus laoreet.
                        </p>
                    </li>
                    <li>
                        <a href="<?=PATH?>rotator/3.jpg" title="architecture"></a>
                        <a href="<?=BASE?>produkt/progress_tajga_/3" ></a>
                        <p style="top:4px; left:4px; width:306px; height:auto">
                            <span class="title">JQuery Banner Rotator</span><br/>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Sed sed elit eget purus consequat tempor eu ac mauris. Nulla facilisi.
                            Vivamus consectetur molestie ipsum ac sollicitudin.
                       	</p>
                    </li>-->
                </ul>
            </div>
        </div>
    </div>
</div>
