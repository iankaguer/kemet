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

function kmt_projects($atts=[]){
    global $wpdb;
    // Get the content of the post or page


// Search for the [kemet_projects] shortcode
    if (isset($atts['title'])) {
        $table_name = $wpdb->prefix . 'kemet_projects';
        $table_name1 = $wpdb->prefix . 'kemet_projects_groups';
        $table_name2 = $wpdb->prefix . 'kemet_projects_categories';

        $title = $atts['title'];
        //get only one
        $menu = $wpdb->get_row("SELECT * FROM $table_name2 WHERE short_code = '$title'", ARRAY_A);

        $cats = $wpdb->get_results("SELECT * FROM $table_name1 WHERE menu_id =  ". $menu['id'], ARRAY_A);

        $categories =[];
        foreach ($cats as $category) {
            $cat = $category;
            $cat['projects'] = $wpdb->get_results(
                    "SELECT * FROM $table_name WHERE group_id = " . $category['id'],
                    ARRAY_A
            );
            $categories[] = $cat;

        }
            $resultats = json_encode($categories, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

    ob_start();
    ?>
        <style>
        div#kp-projects-group {
            display: flex;
            flex-wrap: wrap;
            gap: .5em;
        }
        .kp-group-wrapper {
            width: 30%;
            aspect-ratio: 4/3;
            background-size: cover;
            display: flex;
            align-items: flex-end;
        }
        .kp-group-title{
            background-color: rgba(0,0,0,0.5);
            color: white;
            padding: 0.5em;
            width: 100%;
            overflow-x: hidden;
            font-size: 1.2em;
            font-weight: bold;
        }

        .kp-group-pop-up {
            z-index: 110000;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 100vw;
            height: 100vh;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;
        }
        .kp-group-pop-up-content-project {

            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            align-items: flex-end;
            width: calc(100vw - 100px);
            height: calc(100vh - 100px);
        }
        .kp-group-pop-up-content-projects-wrapper-project {
            display: flex;
            flex-direction: row;
            overflow: auto;
            width: calc(100vw - 100px);
        }
        .kp-group-pop-up-content-description-wrapper-prev,
        .kp-group-pop-up-content-description-wrapper-next {
            display: flex;
            z-index: 111000;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            margin: auto;
            background-color: rgba(0,0,0,0.5);
            color: white;
            font-size: 2em;
            cursor: pointer;
            position: absolute;
        }
        .kp-group-pop-up-content-description-wrapper-next {
            right: 0;
        }
        .kp-group-pop-up-content-description-wrapper-prev {
            left: 0;
        }
        .kp-group-pop-up-content-description-wrapper-block {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
        }
        .kp-group-pop-up-content-projects-wrapper {
            display: flex;
            flex-wrap: nowrap;
            overflow: auto;
        }
        .kp-group-pop-up-content {
            width: calc(100vw - 100px);
        }
        .kp-group-pop-up-caret-left,
        .kp-group-pop-up-caret-right, .kp-group-pop-up-close {
            display: flex;
            align-items: center;
            border-left: 1px #ededed solid;
            justify-content: center;
            width: 50px;
            height: 50px;
            background-color: rgb(255,255,255);
            color: black;
            font-size: 2em;
            cursor: pointer;
        }
        .kp-group-pop-up-action {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
        }
        .kp-group-pop-up-header {
            display: flex;
            width: 100%;
            flex-wrap: nowrap;
            justify-content: space-between;
            border-bottom: 1px #ededed solid;
            align-items: center;
            padding-left: 1em;
        }
        .kp-group-pop-up-content-project-title {
            text-align: center;
            width: 100%;
            font-size: 1.5em;
            padding: .5em;
            background: rgba(255,255,255,0.5);
            align-items: center;
            justify-content: center;
        }
        p.kp-group-pop-up-content-description {
            padding: 1em;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            text-align: justify;
        }
        .kp-group-pop-up-title {
            font-size: 1.4em;
            font-weight: 600;
        }
        .kp-group-pop-up-content-description-show{
            white-space: normal !important;
        }
        span.kp-group-pop-up-content-description-show-more {
            border: 1px #ededed solid;
            padding: .5em;
            cursor: pointer;
            right: 0;
            position: absolute;
            margin-right: 2em;
        }
        .kp-group-pop-up-content-project-body-side {
            width: 20vw;
            padding: 1.5em;
        }
        .kp-group-pop-up-content-project-body-main {
            width: 80vw;
            padding: .5em;
           text-align: justify;
        }
        .kp-group-pop-up-content-project-body {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            width: calc(100vw - 100px);
        }
        .kp-group-pop-up-content-project-title-caroussel {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: center;
        }
        .kp-group-pop-up-content-project-title-caroussel > img{
            width: 70px;
            height: 50px;
            margin: 0.5em;
            cursor: pointer;
            border: 2px white solid;
        }
        </style>

        <div class="kp-projects-group" id="kp-projects-group">

        </div>
        <div class="kp-pop-section" id="kp-pop-section"></div>

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
            let categoriesList = <?php echo $resultats; ?>;
            console.log(categoriesList);
            let selectedCategory = 0;
            let selectedProject = 0;

            const scrollY = document.body.style.top;
            document.body.style.position = '';
            document.body.style.top = '';
            window.scrollTo(0, parseInt(scrollY || '0') * -1);



            function hideAllPopUp() {
                document.getElementById('kp-pop-section').innerHTML = '';
                document.body.style.position = '';
                document.body.style.top = '';
            }

            function showProject(){
                let allProj = document.querySelectorAll('.kp-group-pop-up-content-project-inner');
                for (let i = 0; i < allProj.length; i++) {
                    let project = allProj[i];
                    let projectId = parseInt(project.id.replace('kp-projet-', ''));
                    console.log(projectId)
                    console.log("selected ",selectedProject)
                    //categoryPopUpContentProjectInner.id = 'kp-projet-' + project.id;
                    if (projectId === parseInt(categoriesList[selectedCategory].projects[selectedProject].id)) {
                        project.style.display = 'block';
                    }else {
                        project.style.display = 'none';
                    }
                }
            }

            function nextProject() {
                if (selectedProject < categoriesList[selectedCategory].projects.length - 1) {
                    selectedProject++;
                }else   {
                    selectedProject = 0;
                }

                showProject()
            }

            function previousProject(){
                if (selectedProject > 0) {
                    selectedProject--;
                }else   {
                    selectedProject = categoriesList[selectedCategory].projects.length - 1;
                }
                showProject()
            }
            function setMainImage(imgUrl){

                //get parent with class kp-group-pop-up-content-project
                let parent = event.target;
                while (parent && !parent.classList.contains('kp-group-pop-up-content-project')) {
                    parent = parent.parentElement;
                }
                parent.style.backgroundImage = 'url(' + imgUrl + ')';

            }

            function showPopUp() {
                let selectedIndex = selectedCategory;
                //hideAllPopUp();

                document.body.style.position = 'fixed';
                document.body.style.top = `-${window.scrollY}px`;


                let categoryPopUp = document.createElement('div');
                categoryPopUp.classList.add('kp-group-pop-up');
                let categoryPopUpHeader = document.createElement('div');
                categoryPopUpHeader.classList.add('kp-group-pop-up-header');
                let categoryPopUpTitle = document.createElement('div');
                categoryPopUpTitle.classList.add('kp-group-pop-up-title');
                categoryPopUpTitle.innerHTML = categoriesList[selectedIndex].title;
                categoryPopUpHeader.appendChild(categoryPopUpTitle);

                let categoryPopUpCaretLeft = document.createElement('div');
                categoryPopUpCaretLeft.classList.add('kp-group-pop-up-caret-left');
                categoryPopUpCaretLeft.innerHTML = '<';
                categoryPopUpCaretLeft.addEventListener('click', function () {
                    selectedCategory = selectedCategory - 1;
                    if (selectedCategory < 0) {
                        selectedCategory = categoriesList.length - 1;
                    }
                    selectedProject = 0;
                    showPopUp();
                });

                let categoryPopUpCaretRight = document.createElement('div');
                categoryPopUpCaretRight.classList.add('kp-group-pop-up-caret-right');
                categoryPopUpCaretRight.innerHTML = '>';
                categoryPopUpCaretRight.addEventListener('click', function () {
                    selectedCategory = selectedCategory + 1;
                    if (selectedCategory >= categoriesList.length) {
                        selectedCategory = 0;
                    }
                    selectedProject = 0;
                    showPopUp();
                });


                let categoryPopUpClose = document.createElement('div');
                categoryPopUpClose.classList.add('kp-group-pop-up-close');
                categoryPopUpClose.innerHTML = 'x';
                categoryPopUpClose.addEventListener('click', function () {
                    //
                    hideAllPopUp();
                });


                let divAction = document.createElement('div');
                divAction.classList.add('kp-group-pop-up-action');

                divAction.appendChild(categoryPopUpCaretLeft);
                divAction.appendChild(categoryPopUpCaretRight);
                divAction.appendChild(categoryPopUpClose);
                categoryPopUpHeader.appendChild(divAction);

                let categoryPopUpContent = document.createElement('div');
                categoryPopUpContent.classList.add('kp-group-pop-up-content');

                let categoryPopUpContentDescriptionWrapper = document.createElement('div');
                categoryPopUpContentDescriptionWrapper.classList.add('kp-group-pop-up-content-description-wrapper');
                let categoryPopUpContentDescription = document.createElement('p');
                categoryPopUpContentDescription.classList.add('kp-group-pop-up-content-description');
                categoryPopUpContentDescription.innerHTML = categoriesList[selectedIndex].description;
                //add show more button to description
                let categoryPopUpContentDescriptionShowMore = document.createElement('span');
                categoryPopUpContentDescriptionShowMore.classList.add('kp-group-pop-up-content-description-show-more');
                categoryPopUpContentDescriptionShowMore.innerHTML = 'Show more';
                categoryPopUpContentDescriptionShowMore.addEventListener('click', function () {
                    if (categoryPopUpContentDescription.classList.contains('kp-group-pop-up-content-description-show')) {
                        categoryPopUpContentDescription.classList.remove('kp-group-pop-up-content-description-show');
                        categoryPopUpContentDescriptionShowMore.innerHTML = 'Show more';
                    }else {
                        categoryPopUpContentDescription.classList.add('kp-group-pop-up-content-description-show');
                        categoryPopUpContentDescriptionShowMore.innerHTML = 'Show less';
                    }
                });


                categoryPopUpContentDescriptionWrapper.appendChild(categoryPopUpContentDescription);
                categoryPopUpContentDescriptionWrapper.appendChild(categoryPopUpContentDescriptionShowMore);


                let categoryPopUpContentProjectsWrapper = document.createElement('div');
                categoryPopUpContentProjectsWrapper.classList.add('kp-group-pop-up-content-projects-wrapper');

                let categoryPopUpContentProjectsWrapperProject = document.createElement('div');
                categoryPopUpContentProjectsWrapperProject.classList.add('kp-group-pop-up-content-projects-wrapper-project');

                for (let i=0; i<categoriesList[selectedIndex].projects.length; i++){
                    let project = categoriesList[selectedIndex].projects[i];
                    let categoryPopUpContentProject = document.createElement('div');
                    categoryPopUpContentProject.classList.add('kp-group-pop-up-content-project');
                    categoryPopUpContentProject.style.backgroundImage = 'url(' + project.img1 + ')';

                    let categoryPopUpContentProjectTitle = document.createElement('div');
                    categoryPopUpContentProjectTitle.classList.add('kp-group-pop-up-content-project-title');

                    let spanTitle = document.createElement('div');
                    spanTitle.classList.add('kp-group-pop-up-content-project-title-span');
                    spanTitle.innerHTML = project.name;
                    categoryPopUpContentProjectTitle.appendChild(spanTitle);

                    let divCaroussel = document.createElement('div');
                    divCaroussel.classList.add('kp-group-pop-up-content-project-title-caroussel');
                    if (project.img1 !== null && project.img1 !== ''){
                        let img1 = document.createElement('img');
                        img1.src = project.img1;
                        img1.addEventListener('click', function () {
                            setMainImage(project.img1)
                        });
                        divCaroussel.appendChild(img1);
                    }
                    if (project.img2 !== null && project.img2 !== ''){
                        let img2 = document.createElement('img');
                        img2.src = project.img2;
                        img2.addEventListener('click', function () {
                            setMainImage(project.img2)
                        });
                        divCaroussel.appendChild(img2);
                    }
                    if (project.img3 !== null && project.img3 !== ''){
                        let img3 = document.createElement('img');
                        img3.src = project.img3;
                        img3.addEventListener('click', function () {
                            setMainImage(project.img3)
                        });
                        divCaroussel.appendChild(img3);
                    }
                    if (project.img4 !== null && project.img4 !== ''){
                        let img4 = document.createElement('img');
                        img4.src = project.img4;
                        img4.addEventListener('click', function () {
                            setMainImage(project.img4)
                        });
                        divCaroussel.appendChild(img4);
                    }
                    if (project.img5 !== null && project.img5 !== ''){
                        let img5 = document.createElement('img');
                        img5.src = project.img5;
                        img5.addEventListener('click', function () {
                            setMainImage(project.img5)
                        });
                        divCaroussel.appendChild(img5);
                    }
                    if (project.img6 !== null && project.img6 !== ''){
                        let img6 = document.createElement('img');
                        img6.src = project.img6;
                        img6.addEventListener('click', function () {
                            setMainImage(project.img6)
                        });
                        divCaroussel.appendChild(img6);
                    }
                    if (project.img7 !== null && project.img7 !== ''){
                        let img7 = document.createElement('img');
                        img7.src = project.img7;
                        img7.addEventListener('click', function () {
                            setMainImage(project.img7)
                        });
                        divCaroussel.appendChild(img7);
                    }

                    categoryPopUpContentProjectTitle.appendChild(divCaroussel);
                    categoryPopUpContentProject.appendChild(categoryPopUpContentProjectTitle);


                    let categoryPopUpContentProjectBody = document.createElement('div');
                    categoryPopUpContentProjectBody.classList.add('kp-group-pop-up-content-project-body');

                    let categoryPopUpContentProjectBodySide = document.createElement('div');
                    categoryPopUpContentProjectBodySide.classList.add('kp-group-pop-up-content-project-body-side');


                    if (project.localisation != null && project.localisation !== ''){
                        let projectDivLocalisation = document.createElement('div');
                        projectDivLocalisation.innerHTML = 'Localisation : <strong>' + project.localisation + '</strong>'
                        categoryPopUpContentProjectBodySide.appendChild(projectDivLocalisation);
                    }
                    if (project.client != null && project.client !== ''){
                        let projectDivClient = document.createElement('div');
                        projectDivClient.innerHTML = 'Client : <strong>' + project.client + '</strong>';
                        categoryPopUpContentProjectBodySide.appendChild(projectDivClient);
                    }
                    if (project.taille != null && project.taille !== ''){
                        let projectDivSurface = document.createElement('div');
                        projectDivSurface.innerHTML = 'Surface : <strong>' + project.taille + '</strong>';
                        categoryPopUpContentProjectBodySide.appendChild(projectDivSurface);
                    }
                    if (project.date != null && project.date !== ''){
                        let projectDivAnnee = document.createElement('div');
                        projectDivAnnee.innerHTML = 'Date : <strong>' + project.date + '</strong>';
                        categoryPopUpContentProjectBodySide.appendChild(projectDivAnnee);
                    }

                    categoryPopUpContentProjectBody.appendChild(categoryPopUpContentProjectBodySide);

                    let categoryPopUpContentProjectBodyMain = document.createElement('div');
                    categoryPopUpContentProjectBodyMain.classList.add('kp-group-pop-up-content-project-body-main');
                    categoryPopUpContentProjectBodyMain.innerHTML = project.description;


                    categoryPopUpContentProjectBody.appendChild(categoryPopUpContentProjectBodyMain);




                    let categoryPopUpContentProjectInner = document.createElement('div');
                    categoryPopUpContentProjectInner.classList.add('kp-group-pop-up-content-project-inner');
                    categoryPopUpContentProjectInner.id = 'kp-projet-' + project.id;

                    categoryPopUpContentProjectInner.appendChild(categoryPopUpContentProject);
                    categoryPopUpContentProjectInner.appendChild(categoryPopUpContentProjectBody);
                    categoryPopUpContentProjectsWrapperProject.appendChild(categoryPopUpContentProjectInner);

                }

                let categoryPopUpContentDescriptionWrapperNext = document.createElement('div');
                categoryPopUpContentDescriptionWrapperNext.classList.add('kp-group-pop-up-content-description-wrapper-next');
                categoryPopUpContentDescriptionWrapperNext.innerHTML = '>';
                categoryPopUpContentDescriptionWrapperNext.addEventListener('click', function () {
                    selectedProject = selectedProject + 1;
                    if (selectedProject >= categoriesList[selectedIndex].projects.length) {
                        selectedProject = 0;
                    }
                    showProject();
                });

                let categoryPopUpContentDescriptionWrapperPrev = document.createElement('div');
                categoryPopUpContentDescriptionWrapperPrev.classList.add('kp-group-pop-up-content-description-wrapper-prev');
                categoryPopUpContentDescriptionWrapperPrev.innerHTML = '<';
                categoryPopUpContentDescriptionWrapperPrev.addEventListener('click', function () {
                    selectedProject = selectedProject - 1;
                    if (selectedProject < 0) {
                        selectedProject = categoriesList[selectedIndex].projects.length - 1;
                    }
                    showProject();
                });

                let categoryPopUpContentDescriptionWrapperBlock = document.createElement('div');
                categoryPopUpContentDescriptionWrapperBlock.classList.add('kp-group-pop-up-content-description-wrapper-block');

                categoryPopUpContentDescriptionWrapperBlock.appendChild(categoryPopUpContentDescriptionWrapperPrev);
                categoryPopUpContentDescriptionWrapperBlock.appendChild(categoryPopUpContentProjectsWrapperProject);
                categoryPopUpContentDescriptionWrapperBlock.appendChild(categoryPopUpContentDescriptionWrapperNext);
                categoryPopUpContentProjectsWrapper.appendChild(categoryPopUpContentDescriptionWrapperBlock);
                categoryPopUpContent.appendChild(categoryPopUpContentDescriptionWrapper);
                categoryPopUpContent.appendChild(categoryPopUpContentProjectsWrapper);
                categoryPopUp.appendChild(categoryPopUpHeader);
                categoryPopUp.appendChild(categoryPopUpContent);
                document.getElementById('kp-pop-section').appendChild(categoryPopUp);

                showProject();
            }


            for (let i=0; i<categoriesList.length; i++){
                let category = categoriesList[i];

                let divWrapper = document.createElement('div');
                divWrapper.classList.add('kp-group-wrapper');
                divWrapper.style.backgroundImage = 'url(' + category.cover + ')';
                let divTitle = document.createElement('div');
                divTitle.classList.add('kp-group-title');
                divTitle.innerHTML = category.title;
                divWrapper.appendChild(divTitle);
                divWrapper.addEventListener('click', function () {
                    selectedCategory = i;
                    showPopUp();
                });


                document.getElementById('kp-projects-group').appendChild(divWrapper);
            }
        });
        </script>

    <?php
    return ob_get_clean();

    }
}

add_shortcode('kemet_realisation', 'kemet_rea');
add_shortcode('kemet_3d', 'kemet_3d');
add_shortcode('kemet_design', 'kemet_design');

add_shortcode('kemet_detailled_project', 'kemet_detailled_project');



add_shortcode('kemet_projects', 'kmt_projects');