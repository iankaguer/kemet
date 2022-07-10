<?php
/**
 * @package Kemet_Theme
 * @version 1.0.0
 */
/*
Plugin Name: kemet projects
Plugin URI: https://github.com/iankaguer/kemet
Description: my frist plugin • kemet projects . This is a simple plugin to create a projects table in the database. It also allows you to add, edit, delete and display projects. made 4 Kemet_Studio.
Author: ian kaguer
Version: 1.0
Author URI: "https://github.com/iankaguer"
*/

use App\KmtProjectPlugin;


 if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

$plugin = new KmtProjectPlugin(__FILE__ );

function kemet_rea(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'kemet_projects';
	
	$result = $wpdb->get_results("SELECT * FROM $table_name where description = 'realisation' order by id desc"); //
	ob_start();
	?>
        <style>
            .kemet-custom-slider { display: none; }
            .kemet-slider-container {
                max-width: 800px;
                position: relative;
                margin: auto;
            }
            .prev, .next {
                cursor: pointer;
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 60px;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                color: white;
                font-size: 30px;
                background-color: rgba(0,0,0,0);
                transition: background-color 0.6s ease;
            }
            .prev{ left: 15px; }
            .next { right: 15px; }
            .prev:hover, .next:hover {
                background-color: rgba(0,0,0,0.5);
            }
            .kemet-slide-text {
                position: absolute;
                color: #ffffff;
                bottom: 0;
                width: 100%;
                text-align: center;
                background: #00000047;
            }
            .kemet-slide-text h4{
                font-size: 1.4em;
                margin: 0;
            }
            .kemet-slide-text p{
                font-size: 1em;
                margin: .5em;
            }
            .kemet-slide-index {
                color: #ffffff;
                font-size: 13px;
                padding: 15px;
                position: absolute;
                top: 0;
            }
            .kemet-slide-img{
                width: 100%;
                height: 300px;
                object-fit: cover;
                object-position: center;
            }
           
            .active, .dot:hover { background-color: #111111; }
            .fade {
                animation-name: fade;
                animation-duration: 1s;
            }
            @keyframes fade {
                from {opacity: 0}
                to {opacity: 1}
            }
        </style>

    <style>
        .kemet-project-content{
            display: none;
            flex-direction: column;
            align-items: baseline;
            bottom: 0;
            position: absolute;
            width: 100%;
            background-color: #000000a3;
            color: white;
        }
        .kemet-project-wrapper{
            min-width: 300px;
            height: calc(300px * 3/4);
            transition: all 0.5s ease-out;
            position: relative;

        }
        .kemet-wrap{
            position: relative;
            height: 100%;
            overflow: hidden;
        }
        .kemet-project{
            display: block;
            position: relative;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: all 0.5s ease-out;

        }
        .kemet-project-content h4{
            font-size: 1.1em;
            margin: 0;
            padding: .5em;
            text-transform: uppercase;
        }
        .kemet-prev {
            display: flex;
            position: absolute;
            left: 0;
            top: 0;
            color: #fff;
            font-size: 1.5em;
            bottom: 0;
            align-items: center;
            overflow: hidden;
            padding: .5em;
            background: #00000073;
        }
        .kemet-next {
            display: flex;
            position: absolute;
            right: 0;
            top: 0;
            color: #fff;
            font-size: 1.5em;
            bottom: 0;
            align-items: center;
            overflow: hidden;
            padding: .5em;
            background: #00000073;
        }
        .kemet-realisation::-webkit-scrollbar {
            display: none;
        }
        .kemet-project-content p{
            font-size: .9em;
            margin: 0;
            padding:0 .5em;
            color: #DBA80A;
        }
        .kemet-project-wrapper:hover .kemet-project-content,
        .kemet-project-wrapper:focus .kemet-project-content {
            display: flex;
        }

        .kemet-project-wrapper:hover .kemet-project,
        .kemet-project-wrapper:focus .kemet-project {
            transform: scale(1.2);
        }
        .kemet-realisation{
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 1em;
            overflow-x: auto;
        }
        
        .kemet-shortocde{
            display: flex;
            position: relative;
        }

    </style>
    <div class="kemet-shortocde">
        <div class="kemet-realisation">
            <?php  foreach ($result as $project) : ?>
                <div class="kemet-project-wrapper"
                     url1="<?php echo $project->img1; ?>"
                     url2="<?php echo $project->img2; ?>"
                     url3="<?php echo $project->img3; ?>"
                     url4="<?php echo $project->img4; ?>"
                     url5="<?php echo $project->img5; ?>"
                >
                    <div class="kemet-wrap" >
                        <div class='kemet-project' style="background-image: url(<?php echo $project->img1; ?>)">
    
                        </div>
                    </div>
                    <div class='kemet-project-content'>
                        <h4><?php echo $project->name; ?></h4>
                        <p><?php echo $project->localisation; ?></p>
                    </div>
                </div>
                
            <?php endforeach; ?>
        </div>
    </div>
    <div class='kemet-showme' id='kemet-showme' style="height: 450px; width: 600px;">
    </div>
    //load script javascript
    <script>
        function docReady(fn) {
            // see if DOM is already available
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                // call on next available tick
                setTimeout(fn, 1);
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        }

        

        docReady(function () {
            var slideIndex = 1;
            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            function currentSlide(n) {
                showSlides(slideIndex = n);
            }
            function sideScroll(element,direction,speed,distance,step){
                scrollAmount = 0;
                var slideTimer = setInterval(function(){
                    if(direction == 'left'){
                        element.scrollLeft -= step;
                    } else {
                        element.scrollLeft += step;
                    }
                    scrollAmount += step;
                    if(scrollAmount >= distance){
                        window.clearInterval(slideTimer);
                    }
                }, speed);
            }
            if (isOverflown()) {
                let container = document.querySelector('.kemet-realisation');
                let kemetDiv = document.querySelector('.kemet-shortocde');
                kemetDiv.style.position = 'relative';
                
                let leftArrow = document.createElement('div');
                leftArrow.classList.add('kemet-prev');
                leftArrow.innerHTML = '<span><</span>';
                leftArrow.addEventListener('click', function(){
                    sideScroll(container,'left',25,300,10);
                });
                kemetDiv.appendChild(leftArrow);
                
                let rightArrow = document.createElement('div');
                rightArrow.classList.add('kemet-next');
                rightArrow.innerHTML = '<span>></span>';
                rightArrow.addEventListener('click', function(){
                    document.querySelector('.kemet-realisation').scrollLeft += 300;
                    sideScroll(container,'right',25,300,10);
                    
                });
                kemetDiv.appendChild(rightArrow);
                
            }
            
            function setupSlider(element) {
                //remove all children
                document.getElementById('kemet-showme').innerHTML = '';
                
                let nbImg = 3;
                if (element.getAttribute('url4') != null && element.getAttribute('url4') !== '') {
                    nbImg = nbImg + 1;
                }
                if (element.getAttribute('url5') != null && element.getAttribute('url5') !== '') {
                    nbImg = nbImg + 1;
                }

                let divContainer = document.createElement('div');
                divContainer.classList.add('kemet-slider-container');

                for (let i = 1; i <= nbImg; i++) {
                    let customSlider = document.createElement('div');
                    customSlider.classList.add('kemet-custom-slider', 'fade');

                    let divIndex = document.createElement('div');
                    divIndex.classList.add('kemet-slide-index');
                    divIndex.innerHTML = i + '/' + nbImg;

                    let forImg = document.createElement('img');
                    forImg.classList.add('kemet-slide-img');
                    forImg.src = element.getAttribute('url' + i);
                    
                    let divCaption = document.createElement('div');
                    divCaption.classList.add('kemet-slide-text');
                    divCaption.innerHTML = element.querySelector('.kemet-project-content').innerHTML;

                    customSlider.appendChild(divIndex);
                    customSlider.appendChild(forImg);
                    customSlider.appendChild(divCaption);
                    divContainer.appendChild(customSlider);

                }

                let aPrev = document.createElement('a');
                aPrev.classList.add('prev');
                //aPrev.setAttribute('onclick', 'plusSlides(' + (-1) + ')');
                aPrev.addEventListener("click", function () {
                    plusSlides(-1);
                });
                aPrev.innerHTML = '<';

                let aNext = document.createElement('a');
                aNext.classList.add('next');
                //aNext.setAttribute('onclick', 'plusSlides(' + (1) + ')');
                aNext.addEventListener('click', function () {
                    plusSlides(1);
                });
                aNext.innerHTML = '>';

                divContainer.appendChild(aPrev);
                divContainer.appendChild(aNext);

                document.getElementById('kemet-showme').appendChild(divContainer);
                
                
                showSlides(slideIndex);
            }

           
            
            let projetcs = document.querySelectorAll('.kemet-project-wrapper');
            if (!!projetcs) {
                let element = projetcs[0];
                setupSlider(element);

                for (let i = 0; i < projetcs.length; i++) {
                    projetcs[i].addEventListener('click', function () {
                        let element = this;
                        setupSlider(element);
                        //document.getElementById('kemet-showme').style.display = 'flex';
                    });
                }
            }
            
            //detect if sum of children is greater than parent
            function isOverflown() {
                let element = document.querySelector('.kemet-realisation');
                if(!!element){
                    let sons = element.children;
                    let sonsWidth = 0;
                    for (let i = 0; i < sons.length; i++) {
                        sonsWidth += sons[i].offsetWidth;
                    }
                    return sonsWidth > element.offsetWidth;
                    
                }
                return false;
            }
            

            

            function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName('kemet-custom-slider');
                if (n > slides.length) {
                    slideIndex = 1
                }
                if (n < 1) {
                    slideIndex = slides.length
                }
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = 'none';
                }
                
                slides[slideIndex - 1].style.display = 'block';
            }


        });


    </script>
	
	<?php
	return ob_get_clean();
}
function kemet_3d(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'kemet_projects';
	
	$result = $wpdb->get_results("SELECT * FROM $table_name   where description = 'montage3d' order by id desc");
	ob_start();
	?>

    <style>
        .kemet-custom-slider {
            display: none;
        }

        .kemet-slider-container {
            max-width: 800px;
            position: relative;
            margin: auto;
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            font-size: 30px;
            background-color: rgba(0, 0, 0, 0);
            transition: background-color 0.6s ease;
        }

        .prev {
            left: 15px;
        }

        .next {
            right: 15px;
        }

        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .kemet-slide-text {
            position: absolute;
            color: #ffffff;
            bottom: 0;
            width: 100%;
            text-align: center;
            background: #00000047;
        }

        .kemet-slide-text h4 {
            font-size: 1.4em;
            margin: 0;
        }

        .kemet-slide-text p {
            font-size: 1em;
            margin: .5em;
        }

        .kemet-slide-index {
            color: #ffffff;
            font-size: 13px;
            padding: 15px;
            position: absolute;
            top: 0;
        }

        .kemet-slide-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            object-position: center;
        }

        .active, .dot:hover {
            background-color: #111111;
        }

        .fade {
            animation-name: fade;
            animation-duration: 1s;
        }

        @keyframes fade {
            from {
                opacity: 0
            }
            to {
                opacity: 1
            }
        }
    </style>

    <style>
        .kemet-project-content {
            display: none;
            flex-direction: column;
            align-items: baseline;
            bottom: 0;
            position: absolute;
            width: 100%;
            background-color: #000000a3;
            color: white;
        }

        .kemet-project-wrapper {
            min-width: 300px;
            height: calc(300px * 3 / 4);
            transition: all 0.5s ease-out;
            position: relative;

        }

        .kemet-wrap {
            position: relative;
            height: 100%;
            overflow: hidden;
        }

        .kemet-project {
            display: block;
            position: relative;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: all 0.5s ease-out;

        }

        .kemet-project-content h4 {
            font-size: 1.1em;
            margin: 0;
            padding: .5em;
            text-transform: uppercase;
        }

        .kemet-prev {
            display: flex;
            position: absolute;
            left: 0;
            top: 0;
            color: #fff;
            font-size: 1.5em;
            bottom: 0;
            align-items: center;
            overflow: hidden;
            padding: .5em;
            background: #00000073;
        }

        .kemet-next {
            display: flex;
            position: absolute;
            right: 0;
            top: 0;
            color: #fff;
            font-size: 1.5em;
            bottom: 0;
            align-items: center;
            overflow: hidden;
            padding: .5em;
            background: #00000073;
        }

        .kemet-realisation::-webkit-scrollbar {
            display: none;
        }

        .kemet-project-content p {
            font-size: .9em;
            margin: 0;
            padding: 0 .5em;
            color: #DBA80A;
        }

        .kemet-project-wrapper:hover .kemet-project-content,
        .kemet-project-wrapper:focus .kemet-project-content {
            display: flex;
        }

        .kemet-project-wrapper:hover .kemet-project,
        .kemet-project-wrapper:focus .kemet-project {
            transform: scale(1.2);
        }

        .kemet-realisation {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 1em;
            overflow-x: auto;
        }

        .kemet-shortocde {
            display: flex;
            position: relative;
        }

    </style>
    <div class='kemet-shortocde'>
        <div class='kemet-realisation'>
			<?php foreach ($result as $project) : ?>
                <div class="kemet-project-wrapper"
                     url1="<?php echo $project->img1; ?>"
                     url2="<?php echo $project->img2; ?>"
                     url3="<?php echo $project->img3; ?>"
                     url4="<?php echo $project->img4; ?>"
                     url5="<?php echo $project->img5; ?>"
                >
                    <div class="kemet-wrap">
                        <div class='kemet-project' style="background-image: url(<?php echo $project->img1; ?>)">

                        </div>
                    </div>
                    <div class='kemet-project-content'>
                        <h4><?php echo $project->name; ?></h4>
                        <p><?php echo $project->localisation; ?></p>
                    </div>
                </div>
			
			<?php endforeach; ?>
        </div>
    </div>
    <div class='kemet-showme' id='kemet-showme' style="height: 450px; width: 600px;">
    </div>
    //load script javascript
    <script>
        function docReady(fn) {
            // see if DOM is already available
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                // call on next available tick
                setTimeout(fn, 1);
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        }


        docReady(function () {
            var slideIndex = 1;

            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            function currentSlide(n) {
                showSlides(slideIndex = n);
            }

            function sideScroll(element, direction, speed, distance, step) {
                scrollAmount = 0;
                var slideTimer = setInterval(function () {
                    if (direction == 'left') {
                        element.scrollLeft -= step;
                    } else {
                        element.scrollLeft += step;
                    }
                    scrollAmount += step;
                    if (scrollAmount >= distance) {
                        window.clearInterval(slideTimer);
                    }
                }, speed);
            }

            if (isOverflown()) {
                let container = document.querySelector('.kemet-realisation');
                let kemetDiv = document.querySelector('.kemet-shortocde');
                kemetDiv.style.position = 'relative';

                let leftArrow = document.createElement('div');
                leftArrow.classList.add('kemet-prev');
                leftArrow.innerHTML = '<span><</span>';
                leftArrow.addEventListener('click', function () {
                    sideScroll(container, 'left', 25, 300, 10);
                });
                kemetDiv.appendChild(leftArrow);

                let rightArrow = document.createElement('div');
                rightArrow.classList.add('kemet-next');
                rightArrow.innerHTML = '<span>></span>';
                rightArrow.addEventListener('click', function () {
                    document.querySelector('.kemet-realisation').scrollLeft += 300;
                    sideScroll(container, 'right', 25, 300, 10);

                });
                kemetDiv.appendChild(rightArrow);

            }

            function setupSlider(element) {
                //remove all children
                document.getElementById('kemet-showme').innerHTML = '';

                let nbImg = 3;
                if (element.getAttribute('url4') != null && element.getAttribute('url4') !== '') {
                    nbImg = nbImg + 1;
                }
                if (element.getAttribute('url5') != null && element.getAttribute('url5') !== '') {
                    nbImg = nbImg + 1;
                }

                let divContainer = document.createElement('div');
                divContainer.classList.add('kemet-slider-container');

                for (let i = 1; i <= nbImg; i++) {
                    let customSlider = document.createElement('div');
                    customSlider.classList.add('kemet-custom-slider', 'fade');

                    let divIndex = document.createElement('div');
                    divIndex.classList.add('kemet-slide-index');
                    divIndex.innerHTML = i + '/' + nbImg;

                    let forImg = document.createElement('img');
                    forImg.classList.add('kemet-slide-img');
                    forImg.src = element.getAttribute('url' + i);

                    let divCaption = document.createElement('div');
                    divCaption.classList.add('kemet-slide-text');
                    divCaption.innerHTML = element.querySelector('.kemet-project-content').innerHTML;

                    customSlider.appendChild(divIndex);
                    customSlider.appendChild(forImg);
                    customSlider.appendChild(divCaption);
                    divContainer.appendChild(customSlider);

                }

                let aPrev = document.createElement('a');
                aPrev.classList.add('prev');
                //aPrev.setAttribute('onclick', 'plusSlides(' + (-1) + ')');
                aPrev.addEventListener("click", function () {
                    plusSlides(-1);
                });
                aPrev.innerHTML = '<';

                let aNext = document.createElement('a');
                aNext.classList.add('next');
                //aNext.setAttribute('onclick', 'plusSlides(' + (1) + ')');
                aNext.addEventListener('click', function () {
                    plusSlides(1);
                });
                aNext.innerHTML = '>';

                divContainer.appendChild(aPrev);
                divContainer.appendChild(aNext);

                document.getElementById('kemet-showme').appendChild(divContainer);


                showSlides(slideIndex);
            }


            let projetcs = document.querySelectorAll('.kemet-project-wrapper');
            if (!!projetcs) {
                let element = projetcs[0];
                setupSlider(element);

                for (let i = 0; i < projetcs.length; i++) {
                    projetcs[i].addEventListener('click', function () {
                        let element = this;
                        setupSlider(element);
                        //document.getElementById('kemet-showme').style.display = 'flex';
                    });
                }
            }

            //detect if sum of children is greater than parent
            function isOverflown() {
                let element = document.querySelector('.kemet-realisation');
                if (!!element) {
                    let sons = element.children;
                    let sonsWidth = 0;
                    for (let i = 0; i < sons.length; i++) {
                        sonsWidth += sons[i].offsetWidth;
                    }
                    return sonsWidth > element.offsetWidth;

                }
                return false;
            }


            function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName('kemet-custom-slider');
                if (n > slides.length) {
                    slideIndex = 1
                }
                if (n < 1) {
                    slideIndex = slides.length
                }
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = 'none';
                }

                slides[slideIndex - 1].style.display = 'block';
            }


        });


    </script>
	
	<?php
	return ob_get_clean();
}
function kemet_design(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'kemet_projects';
	
	$result = $wpdb->get_results("SELECT * FROM $table_name   where description = 'designinterieur' order by id desc");
	ob_start();
	?>

    <style>
        .kemet-custom-slider {
            display: none;
        }

        .kemet-slider-container {
            max-width: 800px;
            position: relative;
            margin: auto;
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            font-size: 30px;
            background-color: rgba(0, 0, 0, 0);
            transition: background-color 0.6s ease;
        }

        .prev {
            left: 15px;
        }

        .next {
            right: 15px;
        }

        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .kemet-slide-text {
            position: absolute;
            color: #ffffff;
            bottom: 0;
            width: 100%;
            text-align: center;
            background: #00000047;
        }

        .kemet-slide-text h4 {
            font-size: 1.4em;
            margin: 0;
        }

        .kemet-slide-text p {
            font-size: 1em;
            margin: .5em;
        }

        .kemet-slide-index {
            color: #ffffff;
            font-size: 13px;
            padding: 15px;
            position: absolute;
            top: 0;
        }

        .kemet-slide-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            object-position: center;
        }

        .active, .dot:hover {
            background-color: #111111;
        }

        .fade {
            animation-name: fade;
            animation-duration: 1s;
        }

        @keyframes fade {
            from {
                opacity: 0
            }
            to {
                opacity: 1
            }
        }
    </style>

    <style>
        .kemet-project-content {
            display: none;
            flex-direction: column;
            align-items: baseline;
            bottom: 0;
            position: absolute;
            width: 100%;
            background-color: #000000a3;
            color: white;
        }

        .kemet-project-wrapper {
            min-width: 300px;
            height: calc(300px * 3 / 4);
            transition: all 0.5s ease-out;
            position: relative;

        }

        .kemet-wrap {
            position: relative;
            height: 100%;
            overflow: hidden;
        }

        .kemet-project {
            display: block;
            position: relative;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: all 0.5s ease-out;

        }

        .kemet-project-content h4 {
            font-size: 1.1em;
            margin: 0;
            padding: .5em;
            text-transform: uppercase;
        }

        .kemet-prev {
            display: flex;
            position: absolute;
            left: 0;
            top: 0;
            color: #fff;
            font-size: 1.5em;
            bottom: 0;
            align-items: center;
            overflow: hidden;
            padding: .5em;
            background: #00000073;
        }

        .kemet-next {
            display: flex;
            position: absolute;
            right: 0;
            top: 0;
            color: #fff;
            font-size: 1.5em;
            bottom: 0;
            align-items: center;
            overflow: hidden;
            padding: .5em;
            background: #00000073;
        }

        .kemet-realisation::-webkit-scrollbar {
            display: none;
        }

        .kemet-project-content p {
            font-size: .9em;
            margin: 0;
            padding: 0 .5em;
            color: #DBA80A;
        }

        .kemet-project-wrapper:hover .kemet-project-content,
        .kemet-project-wrapper:focus .kemet-project-content {
            display: flex;
        }

        .kemet-project-wrapper:hover .kemet-project,
        .kemet-project-wrapper:focus .kemet-project {
            transform: scale(1.2);
        }

        .kemet-realisation {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 1em;
            overflow-x: auto;
        }

        .kemet-shortocde {
            display: flex;
            position: relative;
        }

    </style>
    <div class='kemet-shortocde'>
        <div class='kemet-realisation'>
			<?php foreach ($result as $project) : ?>
                <div class="kemet-project-wrapper"
                     url1="<?php echo $project->img1; ?>"
                     url2="<?php echo $project->img2; ?>"
                     url3="<?php echo $project->img3; ?>"
                     url4="<?php echo $project->img4; ?>"
                     url5="<?php echo $project->img5; ?>"
                >
                    <div class="kemet-wrap">
                        <div class='kemet-project' style="background-image: url(<?php echo $project->img1; ?>)">

                        </div>
                    </div>
                    <div class='kemet-project-content'>
                        <h4><?php echo $project->name; ?></h4>
                        <p><?php echo $project->localisation; ?></p>
                    </div>
                </div>
			
			<?php endforeach; ?>
        </div>
    </div>
    <div class='kemet-showme' id='kemet-showme' style="height: 450px; width: 600px;">
    </div>
    //load script javascript
    <script>
        function docReady(fn) {
            // see if DOM is already available
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                // call on next available tick
                setTimeout(fn, 1);
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        }


        docReady(function () {
            var slideIndex = 1;

            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            function currentSlide(n) {
                showSlides(slideIndex = n);
            }

            function sideScroll(element, direction, speed, distance, step) {
                scrollAmount = 0;
                var slideTimer = setInterval(function () {
                    if (direction == 'left') {
                        element.scrollLeft -= step;
                    } else {
                        element.scrollLeft += step;
                    }
                    scrollAmount += step;
                    if (scrollAmount >= distance) {
                        window.clearInterval(slideTimer);
                    }
                }, speed);
            }

            if (isOverflown()) {
                let container = document.querySelector('.kemet-realisation');
                let kemetDiv = document.querySelector('.kemet-shortocde');
                kemetDiv.style.position = 'relative';

                let leftArrow = document.createElement('div');
                leftArrow.classList.add('kemet-prev');
                leftArrow.innerHTML = '<span><</span>';
                leftArrow.addEventListener('click', function () {
                    sideScroll(container, 'left', 25, 300, 10);
                });
                kemetDiv.appendChild(leftArrow);

                let rightArrow = document.createElement('div');
                rightArrow.classList.add('kemet-next');
                rightArrow.innerHTML = '<span>></span>';
                rightArrow.addEventListener('click', function () {
                    document.querySelector('.kemet-realisation').scrollLeft += 300;
                    sideScroll(container, 'right', 25, 300, 10);

                });
                kemetDiv.appendChild(rightArrow);

            }

            function setupSlider(element) {
                //remove all children
                document.getElementById('kemet-showme').innerHTML = '';

                let nbImg = 3;
                if (element.getAttribute('url4') != null && element.getAttribute('url4') !== '') {
                    nbImg = nbImg + 1;
                }
                if (element.getAttribute('url5') != null && element.getAttribute('url5') !== '') {
                    nbImg = nbImg + 1;
                }

                let divContainer = document.createElement('div');
                divContainer.classList.add('kemet-slider-container');

                for (let i = 1; i <= nbImg; i++) {
                    let customSlider = document.createElement('div');
                    customSlider.classList.add('kemet-custom-slider', 'fade');

                    let divIndex = document.createElement('div');
                    divIndex.classList.add('kemet-slide-index');
                    divIndex.innerHTML = i + '/' + nbImg;

                    let forImg = document.createElement('img');
                    forImg.classList.add('kemet-slide-img');
                    forImg.src = element.getAttribute('url' + i);

                    let divCaption = document.createElement('div');
                    divCaption.classList.add('kemet-slide-text');
                    divCaption.innerHTML = element.querySelector('.kemet-project-content').innerHTML;

                    customSlider.appendChild(divIndex);
                    customSlider.appendChild(forImg);
                    customSlider.appendChild(divCaption);
                    divContainer.appendChild(customSlider);

                }

                let aPrev = document.createElement('a');
                aPrev.classList.add('prev');
                //aPrev.setAttribute('onclick', 'plusSlides(' + (-1) + ')');
                aPrev.addEventListener("click", function () {
                    plusSlides(-1);
                });
                aPrev.innerHTML = '<';

                let aNext = document.createElement('a');
                aNext.classList.add('next');
                //aNext.setAttribute('onclick', 'plusSlides(' + (1) + ')');
                aNext.addEventListener('click', function () {
                    plusSlides(1);
                });
                aNext.innerHTML = '>';

                divContainer.appendChild(aPrev);
                divContainer.appendChild(aNext);

                document.getElementById('kemet-showme').appendChild(divContainer);


                showSlides(slideIndex);
            }


            let projetcs = document.querySelectorAll('.kemet-project-wrapper');
            if (!!projetcs) {
                let element = projetcs[0];
                setupSlider(element);

                for (let i = 0; i < projetcs.length; i++) {
                    projetcs[i].addEventListener('click', function () {
                        let element = this;
                        setupSlider(element);
                        //document.getElementById('kemet-showme').style.display = 'flex';
                    });
                }
            }

            //detect if sum of children is greater than parent
            function isOverflown() {
                let element = document.querySelector('.kemet-realisation');
                if (!!element) {
                    let sons = element.children;
                    let sonsWidth = 0;
                    for (let i = 0; i < sons.length; i++) {
                        sonsWidth += sons[i].offsetWidth;
                    }
                    return sonsWidth > element.offsetWidth;

                }
                return false;
            }


            function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName('kemet-custom-slider');
                if (n > slides.length) {
                    slideIndex = 1
                }
                if (n < 1) {
                    slideIndex = slides.length
                }
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = 'none';
                }

                slides[slideIndex - 1].style.display = 'block';
            }


        });


    </script>
	
	<?php
	return ob_get_clean();
}
function kemet_proj(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'kemet_projects';
	
	$result = $wpdb->get_results("SELECT * FROM $table_name  where description = 'projet' order by id desc ");
	ob_start();
	?>

    <style>
        .kemet-custom-slider {
            display: none;
        }

        .kemet-slider-container {
            max-width: 800px;
            position: relative;
            margin: auto;
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            font-size: 30px;
            background-color: rgba(0, 0, 0, 0);
            transition: background-color 0.6s ease;
        }

        .prev {
            left: 15px;
        }

        .next {
            right: 15px;
        }

        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .kemet-slide-text {
            position: absolute;
            color: #ffffff;
            bottom: 0;
            width: 100%;
            text-align: center;
            background: #00000047;
        }

        .kemet-slide-text h4 {
            font-size: 1.4em;
            margin: 0;
        }

        .kemet-slide-text p {
            font-size: 1em;
            margin: .5em;
        }

        .kemet-slide-index {
            color: #ffffff;
            font-size: 13px;
            padding: 15px;
            position: absolute;
            top: 0;
        }

        .kemet-slide-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            object-position: center;
        }

        .active, .dot:hover {
            background-color: #111111;
        }

        .fade {
            animation-name: fade;
            animation-duration: 1s;
        }

        @keyframes fade {
            from {
                opacity: 0
            }
            to {
                opacity: 1
            }
        }
    </style>

    <style>
        .kemet-project-content {
            display: none;
            flex-direction: column;
            align-items: baseline;
            bottom: 0;
            position: absolute;
            width: 100%;
            background-color: #000000a3;
            color: white;
        }

        .kemet-project-wrapper {
            min-width: 300px;
            height: calc(300px * 3 / 4);
            transition: all 0.5s ease-out;
            position: relative;

        }

        .kemet-wrap {
            position: relative;
            height: 100%;
            overflow: hidden;
        }

        .kemet-project {
            display: block;
            position: relative;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: all 0.5s ease-out;

        }

        .kemet-project-content h4 {
            font-size: 1.1em;
            margin: 0;
            padding: .5em;
            text-transform: uppercase;
        }

        .kemet-prev {
            display: flex;
            position: absolute;
            left: 0;
            top: 0;
            color: #fff;
            font-size: 1.5em;
            bottom: 0;
            align-items: center;
            overflow: hidden;
            padding: .5em;
            background: #00000073;
        }

        .kemet-next {
            display: flex;
            position: absolute;
            right: 0;
            top: 0;
            color: #fff;
            font-size: 1.5em;
            bottom: 0;
            align-items: center;
            overflow: hidden;
            padding: .5em;
            background: #00000073;
        }

        .kemet-realisation::-webkit-scrollbar {
            display: none;
        }

        .kemet-project-content p {
            font-size: .9em;
            margin: 0;
            padding: 0 .5em;
            color: #DBA80A;
        }

        .kemet-project-wrapper:hover .kemet-project-content,
        .kemet-project-wrapper:focus .kemet-project-content {
            display: flex;
        }

        .kemet-project-wrapper:hover .kemet-project,
        .kemet-project-wrapper:focus .kemet-project {
            transform: scale(1.2);
        }

        .kemet-realisation {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 1em;
            overflow-x: auto;
        }

        .kemet-shortocde {
            display: flex;
            position: relative;
        }

    </style>
    <div class='kemet-shortocde'>
        <div class='kemet-realisation'>
			<?php foreach ($result as $project) : ?>
                <div class="kemet-project-wrapper"
                     url1="<?php echo $project->img1; ?>"
                     url2="<?php echo $project->img2; ?>"
                     url3="<?php echo $project->img3; ?>"
                     url4="<?php echo $project->img4; ?>"
                     url5="<?php echo $project->img5; ?>"
                >
                    <div class="kemet-wrap">
                        <div class='kemet-project' style="background-image: url(<?php echo $project->img1; ?>)">

                        </div>
                    </div>
                    <div class='kemet-project-content'>
                        <h4><?php echo $project->name; ?></h4>
                        <p><?php echo $project->localisation; ?></p>
                    </div>
                </div>
			
			<?php endforeach; ?>
        </div>
    </div>
    <div class='kemet-showme' id='kemet-showme' style="height: 450px; width: 600px;">
    </div>
    //load script javascript
    <script>
        function docReady(fn) {
            // see if DOM is already available
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                // call on next available tick
                setTimeout(fn, 1);
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        }


        docReady(function () {
            var slideIndex = 1;

            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            function currentSlide(n) {
                showSlides(slideIndex = n);
            }

            function sideScroll(element, direction, speed, distance, step) {
                scrollAmount = 0;
                var slideTimer = setInterval(function () {
                    if (direction == 'left') {
                        element.scrollLeft -= step;
                    } else {
                        element.scrollLeft += step;
                    }
                    scrollAmount += step;
                    if (scrollAmount >= distance) {
                        window.clearInterval(slideTimer);
                    }
                }, speed);
            }

            if (isOverflown()) {
                let container = document.querySelector('.kemet-realisation');
                let kemetDiv = document.querySelector('.kemet-shortocde');
                kemetDiv.style.position = 'relative';

                let leftArrow = document.createElement('div');
                leftArrow.classList.add('kemet-prev');
                leftArrow.innerHTML = '<span><</span>';
                leftArrow.addEventListener('click', function () {
                    sideScroll(container, 'left', 25, 300, 10);
                });
                kemetDiv.appendChild(leftArrow);

                let rightArrow = document.createElement('div');
                rightArrow.classList.add('kemet-next');
                rightArrow.innerHTML = '<span>></span>';
                rightArrow.addEventListener('click', function () {
                    document.querySelector('.kemet-realisation').scrollLeft += 300;
                    sideScroll(container, 'right', 25, 300, 10);

                });
                kemetDiv.appendChild(rightArrow);

            }

            function setupSlider(element) {
                //remove all children
                document.getElementById('kemet-showme').innerHTML = '';

                let nbImg = 3;
                if (element.getAttribute('url4') != null && element.getAttribute('url4') !== '') {
                    nbImg = nbImg + 1;
                }
                if (element.getAttribute('url5') != null && element.getAttribute('url5') !== '') {
                    nbImg = nbImg + 1;
                }

                let divContainer = document.createElement('div');
                divContainer.classList.add('kemet-slider-container');

                for (let i = 1; i <= nbImg; i++) {
                    let customSlider = document.createElement('div');
                    customSlider.classList.add('kemet-custom-slider', 'fade');

                    let divIndex = document.createElement('div');
                    divIndex.classList.add('kemet-slide-index');
                    divIndex.innerHTML = i + '/' + nbImg;

                    let forImg = document.createElement('img');
                    forImg.classList.add('kemet-slide-img');
                    forImg.src = element.getAttribute('url' + i);

                    let divCaption = document.createElement('div');
                    divCaption.classList.add('kemet-slide-text');
                    divCaption.innerHTML = element.querySelector('.kemet-project-content').innerHTML;

                    customSlider.appendChild(divIndex);
                    customSlider.appendChild(forImg);
                    customSlider.appendChild(divCaption);
                    divContainer.appendChild(customSlider);

                }

                let aPrev = document.createElement('a');
                aPrev.classList.add('prev');
                //aPrev.setAttribute('onclick', 'plusSlides(' + (-1) + ')');
                aPrev.addEventListener("click", function () {
                    plusSlides(-1);
                });
                aPrev.innerHTML = '<';

                let aNext = document.createElement('a');
                aNext.classList.add('next');
                //aNext.setAttribute('onclick', 'plusSlides(' + (1) + ')');
                aNext.addEventListener('click', function () {
                    plusSlides(1);
                });
                aNext.innerHTML = '>';

                divContainer.appendChild(aPrev);
                divContainer.appendChild(aNext);

                document.getElementById('kemet-showme').appendChild(divContainer);


                showSlides(slideIndex);
            }


            let projetcs = document.querySelectorAll('.kemet-project-wrapper');
            if (!!projetcs) {
                let element = projetcs[0];
                setupSlider(element);

                for (let i = 0; i < projetcs.length; i++) {
                    projetcs[i].addEventListener('click', function () {
                        let element = this;
                        setupSlider(element);
                        //document.getElementById('kemet-showme').style.display = 'flex';
                    });
                }
            }

            //detect if sum of children is greater than parent
            function isOverflown() {
                let element = document.querySelector('.kemet-realisation');
                if (!!element) {
                    let sons = element.children;
                    let sonsWidth = 0;
                    for (let i = 0; i < sons.length; i++) {
                        sonsWidth += sons[i].offsetWidth;
                    }
                    return sonsWidth > element.offsetWidth;

                }
                return false;
            }


            function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName('kemet-custom-slider');
                if (n > slides.length) {
                    slideIndex = 1
                }
                if (n < 1) {
                    slideIndex = slides.length
                }
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = 'none';
                }

                slides[slideIndex - 1].style.display = 'block';
            }


        });


    </script>
    
    
	
	<?php
	return ob_get_clean();
}
	
	add_shortcode('kemet_realisation', 'kemet_rea');
	add_shortcode('kemet_3d', 'kemet_3d');
	add_shortcode('kemet_design', 'kemet_design');
	add_shortcode('kemet_projet', 'kemet_proj');