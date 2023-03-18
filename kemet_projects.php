<?php
/**
 * @package Kemet_Theme
 * @version 1.0.0
 */

/*
Plugin Name: kemet projects
Plugin URI: https://github.com/iankaguer/kemet
Description: my first wp plugin • kemet projects . This is a simple plugin to create a projects table in the database. It also allows you to add, edit, delete and display projects. made 4 Kemet_Studio.
Author: @iankaguer
Version: 1.0
Author URI: "https://github.com/iankaguer"
*/

use App\KmtProjectPlugin;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require plugin_dir_path(__FILE__) . 'vendor/autoload.php';

$plugin = new KmtProjectPlugin(__FILE__);

function kemet_rea()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'kemet_projects';

    $result = $wpdb->get_results("SELECT * FROM $table_name where description = 'realisation' order by id desc"); //
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
            color: #DBA80A;
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
            min-width: 800px;
            height: calc(800px * 3 / 4);
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
    <div class="kemet-shortocde">
        <div class="kemet-realisation">
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
    <div class='kemet-showme' id='kemet-showme' style="height: 450px; margin-top: 1em;">
    </div>

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
                    slideIndex = 1;
                }
                if (n < 1) {
                    slideIndex = slides.length;
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

function kemet_3d()
{
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
            color: #DBA80A;
            margin: 0;
        }

        .kemet-slide-text p {
            font-size: 1em;
            color: #ffffff;
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
            min-width: 800px;
            height: calc(800px * 3 / 4);
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
    <div class='kemet-showme' id='kemet-showme' style="height: 450px; margin-top: 1em;">
    </div>

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
                    slideIndex = 1;
                }
                if (n < 1) {
                    slideIndex = slides.length;
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

function kemet_design()
{
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
            color: #DBA80A;
            margin: 0;
        }

        .kemet-slide-text p {
            font-size: 1em;
            color: #ffffff;
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
            min-width: 800px;
            height: calc(800px * 3 / 4);
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
    <div class='kemet-showme' id='kemet-showme' style="height: 450px; margin-top: 1em;">
    </div>

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
                    slideIndex = 1;
                }
                if (n < 1) {
                    slideIndex = slides.length;
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

function kemet_proj()
{
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
            color: #DBA80A;
            margin: 0;
        }

        .kemet-slide-text p {
            font-size: 1em;
            color: #ffffff;
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
            min-width: 800px;
            height: calc(800px * 3 / 4);
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
    <div class='kemet-showme' id='kemet-showme' style="height: 450px; margin-top: 1em;">
    </div>

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
                    slideIndex = 1;
                }
                if (n < 1) {
                    slideIndex = slides.length;
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

function kemet_detailled_project()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'kemet_projects_detailles';
    $table_name_2 = $wpdb->prefix . 'kemet_projects_detailles_images';


    $result = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    $resultats = [];
    foreach ($result as $row) {
        $resultImage = $wpdb->get_results("SELECT * FROM $table_name_2 WHERE id_project  = " . $row['id'], ARRAY_A);
        $row['image'] = $resultImage;
        $resultats[] = $row;
    }
    $resultats = json_encode($resultats, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    ob_start();
    ?>
    <style>
        .kemet-detailled-project {
            width: 300px;
            height: calc(300px * 3 / 4);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        .kemet-detailled-project-wrapper {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 0%, rgba(255, 255, 255, 0.5) 100%);
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            opacity: 0;
            cursor: pointer;
        }

        .kemet-detailled-project-wrapper:hover {
            opacity: 1;
        }

        .kemet-detailled-project-wrapper h3 {
            color: #fff;
            font-size: 1.2em;
            margin-bottom: 0;
            padding-bottom: 0;
            padding-left: .4em;
            padding-right: .4em;
            line-height: 0;
        }

        .kemet-detailled-project-wrapper p {
            color: #fff;
            font-size: .8em;
            margin-bottom: 0;
            padding-bottom: 0;
            padding-left: .4em;
            padding-right: .4em;
        }


        div#kemet-detailled-projects {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            gap: .5em;
        }

        .kemet-pop-up-wrapper {
            z-index: 10000;
            display: none;
        }

        .kemet-pop-up {
            z-index: 110000;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 100vw;
            height: 100vh;
            background: #fff;
        }

        .kemet-pop-up-header {
            width: 100%;
            height: 50px;
            border-bottom: 1px solid #bb8e00;
            display: flex;
            justify-content: space-between;
            background: #DBA80A;
        }

        .action-kemet-pop-up > div {
            font-size: 1.5em;
            height: 100%;
            width: 50px;
            /* margin: 0 auto; */
            text-align-last: center;
            border: 1px solid #bb8e00;
            cursor: pointer;
        }

        .action-kemet-pop-up {
            display: flex;
            color: #fff;
        }

        .kemet-pop-up-article-body {
            display: flex;
        }

        .kemet-pop-up-article-header {
            width: 100%;
            height: 300px;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .kemet-pop-up-article-header-inner {
            width: 100%;
            height: 100%;
            background-color: rgba(12, 12, 12, 0.56);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.5em;
            color: white;
        }

        div#kemet-pop-up-content {
            height: calc(100vh - 50px);
            overflow-x: auto;
        }

        img.kemet-pop-up-article-image {
            width: 80%;
        }

        .kemet-pop-up-article-side {
            width: 20%;
            padding: .8em;
            font-size: 80%;
            background: #f8f8f8;
        }

        .kemet-pop-up-article-main {
            width: 80%;
            text-align: justify;
        }

        .kemet-pop-up-article-main > p {
            padding: 1em;
            margin: 0;
        }

        .kemet-pop-up-title {
            display: flex;
            align-items: center;
            padding-left: 1em;
            color: #fff;
        }
    </style>

    <div class="kemet-detailled-projects" id="kemet-detailled-projects">
    </div>
    <div class="kemet-pop-up-wrapper" id="kemet-pop-up-wrapper">
    <div class="kemet-pop-up">
        <div class="kemet-pop-up-header">
            <div class="kemet-pop-up-title">

            </div>
            <div class="action-kemet-pop-up">
                <div class='left-kemet-pop-up'>
                    <
                </div>
                <div class='right-kemet-pop-up'>
                    >
                </div>
                <div class='close-kemet-pop-up'>
                    x
                </div>
            </div>

        </div>
        <div class="kemet-pop-up-content" id="kemet-pop-up-content">

        </div>

    </div>
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
            let project_list = <?php echo $resultats; ?>;
            let selectedProject = 0;

            const scrollY = document.body.style.top;
            document.body.style.position = '';
            document.body.style.top = '';
            window.scrollTo(0, parseInt(scrollY || '0') * -1);


            for (let i = 0; i < project_list.length; i++) {
                let element = project_list[i];
                console.log(element);

                //console.log(element);
                let divContainer = document.createElement('div');
                let divWrapper = document.createElement('div');

                divWrapper.classList.add('kemet-detailled-project-wrapper');
                divContainer.classList.add('kemet-detailled-project');
                divContainer.setAttribute('id', 'kemet-detailled-project-' + element.id);
                //divContainer.style.background_image = 'url(' + element.image[0]?.img + ')';
                if (element.image.length > 0) {
                    divContainer.style.backgroundImage = "url('" + element.image[0].img + "')";
                }
                let h3Title = document.createElement('h3');
                h3Title.innerHTML = element.title;
                let pSite = document.createElement('p');
                pSite.innerHTML = element.site;
                divWrapper.appendChild(h3Title);
                divWrapper.appendChild(pSite);
                divWrapper.addEventListener('click', function () {
                    selectedProject = element.id;
                    console.log(selectedProject);
                    showPopUp();
                });
                divContainer.appendChild(divWrapper);
                document.getElementById('kemet-detailled-projects').appendChild(divContainer);

                let divPopUp = document.createElement('div');
                divPopUp.classList.add('kemet-pop-up-article');
                divPopUp.setAttribute('id', 'kemet-pop-up-article-' + element.id);
                let divPopUpHeader = document.createElement('div');
                divPopUpHeader.classList.add('kemet-pop-up-article-header');
                if (element.image.length > 0) {
                    divPopUpHeader.style.backgroundImage = "url('" + element.image[0].img + "')";
                }
                let divPopUpHeaderInner = document.createElement('div');
                divPopUpHeaderInner.classList.add('kemet-pop-up-article-header-inner');
                divPopUpHeaderInner.innerHTML = element.title;
                divPopUpHeader.appendChild(divPopUpHeaderInner);

                let divPopUpBody = document.createElement('div');
                divPopUpBody.classList.add('kemet-pop-up-article-body');
                let divPopSide = document.createElement('div');
                divPopSide.classList.add('kemet-pop-up-article-side');
                let divPopUpMain = document.createElement('div');
                divPopUpMain.classList.add('kemet-pop-up-article-main');

                let divElementTitle = document.createElement('div');
                divElementTitle.innerHTML = element.title;
                let divElementSite = document.createElement('div');
                divElementSite.innerHTML = "Site : " + element.site;
                let divElementCollaborateur = document.createElement('div');
                divElementCollaborateur.innerHTML = "Collaborateur(s) : " + element.collaborateurs;
                let divElementDate = document.createElement('div');
                divElementDate.innerHTML = "Date : " + element.date;
                let divElementStatut = document.createElement('div');
                divElementStatut.innerHTML = "Statut : " + element.status;
                let divElementTaille = document.createElement('div');
                divElementTaille.innerHTML = "Taille : " + element.taille;

                //append multiple childs to divPopSide
                divPopSide.appendChild(divElementTitle);
                divPopSide.appendChild(divElementSite);
                divPopSide.appendChild(divElementCollaborateur);
                divPopSide.appendChild(divElementDate);
                divPopSide.appendChild(divElementTaille);
                divPopSide.appendChild(divElementStatut);

                let pText = document.createElement('p');
                pText.innerHTML = element.description;
                divPopUpMain.appendChild(pText);

                for (let j = 0; j < element.image.length; j++) {
                    let img = document.createElement('img');
                    img.classList.add('kemet-pop-up-article-image');
                    img.src = element.image[j].img;
                    divPopUpMain.appendChild(img);
                }

                // divPopUpBody.appendChild(divPopSide, divPopUpMain);
                divPopUpBody.appendChild(divPopSide);
                divPopUpBody.appendChild(divPopUpMain);

                //divPopUp.appendChild(divPopUpHeader, divPopUpBody);
                divPopUp.appendChild(divPopUpHeader);
                divPopUp.appendChild(divPopUpBody);
                document.getElementById('kemet-pop-up-content').appendChild(divPopUp);


            }

            function hideAllPopUp() {
                let popUpList = document.getElementsByClassName('kemet-pop-up-article');
                for (let i = 0; i < popUpList.length; i++) {
                    popUpList[i].style.display = 'none';
                }
            }

            function showPopUp() {
                hideAllPopUp();
                //let popUpList = document.getElementsByClassName('kemet-pop-up-article');
                //popUpList[selectedProject].style.display = 'block';
                document.body.style.position = 'fixed';
                document.body.style.top = `-${window.scrollY}px`;

                document.getElementById('kemet-pop-up-wrapper').style.display = 'block';
                document.getElementById('kemet-pop-up-article-' + selectedProject).style.display = 'block';
                document.querySelector(".kemet-pop-up-title").innerHTML = document.getElementById('kemet-pop-up-article-' + selectedProject).querySelector(".kemet-pop-up-article-header-inner").innerHTML;
            }

            let kemetLeft = document.querySelector('.left-kemet-pop-up');
            let kemetRight = document.querySelector('.right-kemet-pop-up');
            let kemetClose = document.querySelector('.close-kemet-pop-up');

            kemetLeft.addEventListener('click', function () {
                let popUpItem = document.getElementById('kemet-pop-up-article-' + selectedProject);
                //get previous div sibling
                let previousSibling = popUpItem.previousElementSibling;
                if (!previousSibling) {
                    previousSibling = popUpItem.parentNode.lastElementChild;
                }
                selectedProject = previousSibling.getAttribute('id').replace('kemet-pop-up-article-', '');

                showPopUp();

            });
            kemetRight.addEventListener('click', function () {
                let popUpItem = document.getElementById('kemet-pop-up-article-' + selectedProject);
                //get next div sibling
                let nextSibling = popUpItem.nextElementSibling;
                if (!nextSibling) {
                    nextSibling = popUpItem.parentNode.firstElementChild;
                }
                selectedProject = nextSibling.getAttribute('id').replace('kemet-pop-up-article-', '');
                showPopUp();
            });
            kemetClose.addEventListener('click', function () {
                document.getElementById('kemet-pop-up-wrapper').style.display = 'none';
                document.body.style.position = '';
                document.body.style.top = '';
            });


        });
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('kemet_realisation', 'kemet_rea');
add_shortcode('kemet_3d', 'kemet_3d');
add_shortcode('kemet_design', 'kemet_design');
add_shortcode('kemet_projet', 'kemet_proj');
add_shortcode('kemet_detailled_project', 'kemet_detailled_project');