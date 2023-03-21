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


function kmt_projects($atts = [])
{
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

        $cats = $wpdb->get_results("SELECT * FROM $table_name1 WHERE menu_id=" . $menu['id'] . " ORDER BY id DESC ", ARRAY_A);

        $categories = [];
        foreach ($cats as $category) {
            $cat = $category;
            $cat['projects'] = $wpdb->get_results(
                "SELECT * FROM $table_name WHERE group_id = " . $category['id'] . ' ORDER BY id DESC ',
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

            .kp-group-title {
                background-color: rgba(0, 0, 0, 0.5);
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
                max-width: 50px;
                margin: auto;
                background-color: rgba(0, 0, 0, 0.5);
                color: white;
                font-size: 2em;
                cursor: pointer;
                position: absolute;
                transition: all 0.2s ease;
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
                transition: all 0.2s ease;
                align-items: center;
                border-left: 1px #ededed solid;
                justify-content: center;
                width: 50px;
                max-width: 50px;
                height: 50px;
                background-color: rgb(255, 255, 255);
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
                background: rgba(255, 255, 255, 0.5);
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

            .kp-group-pop-up-content-description-show {
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

            .kp-group-pop-up-content-project-title-caroussel > img {
                width: 70px;
                height: 50px;
                margin: 0.5em;
                cursor: pointer;
                border: 2px white solid;
            }

            .kp-group-pop-up-content-description-wrapper-next-tooltip,
            .kp-group-pop-up-content-description-wrapper-prev-tooltip {
                display: none;
                z-index: 111000;
                background-color: rgb(0, 0, 0);
                color: white;
                font-size: 12px;
                margin: auto;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                height: 50px;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
            }

            .kp-group-pop-up-caret-left-tooltip,
            .kp-group-pop-up-caret-right-tooltip {
                display: none;
                color: black;
                font-size: 0;
                padding: .5em;
                margin: auto;
                top: 0;
                opacity: 0;
                bottom: 0;
                left: 0;
                right: 0;
                align-items: center;
                justify-content: center;

            }

            .show-tooltip {
                display: flex !important;
                opacity: 1 !important;
                font-size: 14px !important;
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

                function showProject() {
                    let allProj = document.querySelectorAll('.kp-group-pop-up-content-project-inner');
                    for (let i = 0; i < allProj.length; i++) {
                        let project = allProj[i];
                        let projectId = parseInt(project.id.replace('kp-projet-', ''));
                        console.log(projectId);
                        console.log("selected ", selectedProject);
                        //categoryPopUpContentProjectInner.id = 'kp-projet-' + project.id;
                        if (projectId === parseInt(categoriesList[selectedCategory].projects[selectedProject].id)) {
                            project.style.display = 'block';
                        } else {
                            project.style.display = 'none';
                        }
                    }
                }

                function nextProject() {
                    if (selectedProject < categoriesList[selectedCategory].projects.length - 1) {
                        selectedProject++;
                    } else {
                        selectedProject = 0;
                    }

                    showProject();
                }

                function previousProject() {
                    if (selectedProject > 0) {
                        selectedProject--;
                    } else {
                        selectedProject = categoriesList[selectedCategory].projects.length - 1;
                    }
                    showProject();
                }

                function setMainImage(imgUrl) {

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
                    categoryPopUpCaretLeft.innerHTML = `<svg width='40px' height='40px' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg' fill='none'>
                      <path stroke='#000000' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 4l-6 6 6 6'/>
                    </svg>`;
                    //add tooltip
                    let categoryPopUpCaretLeftTooltip = document.createElement('div');
                    categoryPopUpCaretLeftTooltip.classList.add('kp-group-pop-up-caret-left-tooltip');
                    categoryPopUpCaretLeftTooltip.innerHTML = 'Catégorie précédente';
                    categoryPopUpCaretLeft.appendChild(categoryPopUpCaretLeftTooltip);
                    categoryPopUpCaretLeft.addEventListener('mouseover', function () {
                        this.style.maxWidth = '200px';
                        this.style.width = 'auto';
                        this.style.backgroundColor = '#ededed';
                        categoryPopUpCaretLeftTooltip.classList.add('show-tooltip');
                    });
                    categoryPopUpCaretLeft.addEventListener('mouseout', function () {
                        this.style.width = '50px';
                        this.style.maxWidth = '50px';
                        this.style.backgroundColor = '#fff';
                        categoryPopUpCaretLeftTooltip.classList.remove('show-tooltip');
                    });
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

                    //add tooltip
                    let categoryPopUpCaretRightTooltip = document.createElement('div');
                    categoryPopUpCaretRightTooltip.classList.add('kp-group-pop-up-caret-right-tooltip');
                    categoryPopUpCaretRightTooltip.innerHTML = 'Catégorie suivante';
                    categoryPopUpCaretRight.appendChild(categoryPopUpCaretRightTooltip);
                    categoryPopUpCaretRight.addEventListener('mouseover', function () {
                        this.style.maxWidth = '200px';
                        this.style.width = 'auto';
                        this.style.backgroundColor = '#ededed';
                        categoryPopUpCaretRightTooltip.classList.add('show-tooltip');
                    });
                    categoryPopUpCaretRight.addEventListener('mouseout', function () {
                        this.style.width = '50px';
                        this.style.maxWidth = '50px';
                        this.style.backgroundColor = '#FFF';
                        categoryPopUpCaretRightTooltip.classList.remove('show-tooltip');
                    });
                    // categoryPopUpCaretRight.innerHTML = `<svg width='40px' height='40px' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg' fill='none'>
                    //       <path stroke='#000000' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 16l6-6-6-6'/>
                    //     </svg>`;
                    //add svg
                    let categoryPopUpCaretRightSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    categoryPopUpCaretRightSvg.setAttribute('width', '40px');
                    categoryPopUpCaretRightSvg.setAttribute('height', '40px');
                    categoryPopUpCaretRightSvg.setAttribute('viewBox', '0 0 20 20');
                    categoryPopUpCaretRightSvg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                    categoryPopUpCaretRightSvg.setAttribute('fill', 'none');
                    let categoryPopUpCaretRightSvgPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    categoryPopUpCaretRightSvgPath.setAttribute('stroke', '#000000');
                    categoryPopUpCaretRightSvgPath.setAttribute('stroke-linecap', 'round');
                    categoryPopUpCaretRightSvgPath.setAttribute('stroke-linejoin', 'round');
                    categoryPopUpCaretRightSvgPath.setAttribute('stroke-width', '2');
                    categoryPopUpCaretRightSvgPath.setAttribute('d', 'M7 16l6-6-6-6');
                    categoryPopUpCaretRightSvg.appendChild(categoryPopUpCaretRightSvgPath);
                    categoryPopUpCaretRight.appendChild(categoryPopUpCaretRightSvg);

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
                    categoryPopUpClose.innerHTML = `<svg width='800px' height='800px' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' clip-rule='evenodd' d='M19.207 6.207a1 1 0 0 0-1.414-1.414L12 10.586 6.207 4.793a1 1 0 0 0-1.414 1.414L10.586 12l-5.793 5.793a1 1 0 1 0 1.414 1.414L12 13.414l5.793 5.793a1 1 0 0 0 1.414-1.414L13.414 12l5.793-5.793z' fill='#000000'/></svg>`;
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

                    //let categoryPopUpContentDescriptionWrapper = document.createElement('div');
                    //categoryPopUpContentDescriptionWrapper.classList.add('kp-group-pop-up-content-description-wrapper');
                    //let categoryPopUpContentDescription = document.createElement('p');
                    //categoryPopUpContentDescription.classList.add('kp-group-pop-up-content-description');
                    //categoryPopUpContentDescription.innerHTML = categoriesList[selectedIndex].description;
                    //add show more button to description
                    // let categoryPopUpContentDescriptionShowMore = document.createElement('span');
                    // categoryPopUpContentDescriptionShowMore.classList.add('kp-group-pop-up-content-description-show-more');
                    // categoryPopUpContentDescriptionShowMore.innerHTML = 'Show more';
                    // categoryPopUpContentDescriptionShowMore.addEventListener('click', function () {
                    //     if (categoryPopUpContentDescription.classList.contains('kp-group-pop-up-content-description-show')) {
                    //         categoryPopUpContentDescription.classList.remove('kp-group-pop-up-content-description-show');
                    //         categoryPopUpContentDescriptionShowMore.innerHTML = 'Show more';
                    //     }else {
                    //         categoryPopUpContentDescription.classList.add('kp-group-pop-up-content-description-show');
                    //         categoryPopUpContentDescriptionShowMore.innerHTML = 'Show less';
                    //     }
                    // });
                    //
                    // categoryPopUpContentDescriptionWrapper.appendChild(categoryPopUpContentDescription);
                    // categoryPopUpContentDescriptionWrapper.appendChild(categoryPopUpContentDescriptionShowMore);


                    let categoryPopUpContentProjectsWrapper = document.createElement('div');
                    categoryPopUpContentProjectsWrapper.classList.add('kp-group-pop-up-content-projects-wrapper');

                    let categoryPopUpContentProjectsWrapperProject = document.createElement('div');
                    categoryPopUpContentProjectsWrapperProject.classList.add('kp-group-pop-up-content-projects-wrapper-project');

                    for (let i = 0; i < categoriesList[selectedIndex].projects.length; i++) {
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
                        if (project.img1 !== null && project.img1 !== '') {
                            let img1 = document.createElement('img');
                            img1.src = project.img1;
                            img1.addEventListener('click', function () {
                                setMainImage(project.img1);
                            });
                            divCaroussel.appendChild(img1);
                        }
                        if (project.img2 !== null && project.img2 !== '') {
                            let img2 = document.createElement('img');
                            img2.src = project.img2;
                            img2.addEventListener('click', function () {
                                setMainImage(project.img2);
                            });
                            divCaroussel.appendChild(img2);
                        }
                        if (project.img3 !== null && project.img3 !== '') {
                            let img3 = document.createElement('img');
                            img3.src = project.img3;
                            img3.addEventListener('click', function () {
                                setMainImage(project.img3);
                            });
                            divCaroussel.appendChild(img3);
                        }
                        if (project.img4 !== null && project.img4 !== '') {
                            let img4 = document.createElement('img');
                            img4.src = project.img4;
                            img4.addEventListener('click', function () {
                                setMainImage(project.img4);
                            });
                            divCaroussel.appendChild(img4);
                        }
                        if (project.img5 !== null && project.img5 !== '') {
                            let img5 = document.createElement('img');
                            img5.src = project.img5;
                            img5.addEventListener('click', function () {
                                setMainImage(project.img5);
                            });
                            divCaroussel.appendChild(img5);
                        }
                        if (project.img6 !== null && project.img6 !== '') {
                            let img6 = document.createElement('img');
                            img6.src = project.img6;
                            img6.addEventListener('click', function () {
                                setMainImage(project.img6);
                            });
                            divCaroussel.appendChild(img6);
                        }
                        if (project.img7 !== null && project.img7 !== '') {
                            let img7 = document.createElement('img');
                            img7.src = project.img7;
                            img7.addEventListener('click', function () {
                                setMainImage(project.img7);
                            });
                            divCaroussel.appendChild(img7);
                        }

                        categoryPopUpContentProjectTitle.appendChild(divCaroussel);
                        categoryPopUpContentProject.appendChild(categoryPopUpContentProjectTitle);


                        let categoryPopUpContentProjectBody = document.createElement('div');
                        categoryPopUpContentProjectBody.classList.add('kp-group-pop-up-content-project-body');

                        let categoryPopUpContentProjectBodySide = document.createElement('div');
                        categoryPopUpContentProjectBodySide.classList.add('kp-group-pop-up-content-project-body-side');


                        if (project.localisation != null && project.localisation !== '') {
                            let projectDivLocalisation = document.createElement('div');
                            projectDivLocalisation.innerHTML = 'Localisation : <strong>' + project.localisation + '</strong>';
                            categoryPopUpContentProjectBodySide.appendChild(projectDivLocalisation);
                        }
                        if (project.client != null && project.client !== '') {
                            let projectDivClient = document.createElement('div');
                            projectDivClient.innerHTML = 'Client : <strong>' + project.client + '</strong>';
                            categoryPopUpContentProjectBodySide.appendChild(projectDivClient);
                        }
                        if (project.taille != null && project.taille !== '') {
                            let projectDivSurface = document.createElement('div');
                            projectDivSurface.innerHTML = 'Surface : <strong>' + project.taille + '</strong>';
                            categoryPopUpContentProjectBodySide.appendChild(projectDivSurface);
                        }
                        if (project.date != null && project.date !== '') {
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
                    categoryPopUpContentDescriptionWrapperNext.innerHTML = `<svg width='40px' height='40px' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg' fill='none'>
                      <path stroke='#ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 16l6-6-6-6'/>
                    </svg>`;
                    let tooltipProjectNext = document.createElement('div');
                    tooltipProjectNext.classList.add('kp-group-pop-up-content-description-wrapper-next-tooltip');
                    tooltipProjectNext.innerHTML = 'Projet suivant';
                    categoryPopUpContentDescriptionWrapperNext.appendChild(tooltipProjectNext);
                    //add tooltip
                    categoryPopUpContentDescriptionWrapperNext.addEventListener('mouseover', function () {
                        this.style.width = '150px';
                        this.style.maxWidth = '150px';
                        document.querySelector('.kp-group-pop-up-content-description-wrapper-next-tooltip').classList.add('show-tooltip');
                    });
                    categoryPopUpContentDescriptionWrapperNext.addEventListener('mouseout', function () {
                        let tooltip = document.querySelector('.kp-group-pop-up-content-description-wrapper-next-tooltip');
                        this.style.width = '50px';
                        this.style.maxWidth = '50px';
                        if (tooltip !== null) {
                            tooltip.classList.remove('show-tooltip');
                        }
                    });
                    categoryPopUpContentDescriptionWrapperNext.addEventListener('click', function () {
                        selectedProject = selectedProject + 1;
                        if (selectedProject >= categoriesList[selectedIndex].projects.length) {
                            selectedProject = 0;
                        }
                        showProject();
                    });

                    let categoryPopUpContentDescriptionWrapperPrev = document.createElement('div');
                    categoryPopUpContentDescriptionWrapperPrev.classList.add('kp-group-pop-up-content-description-wrapper-prev');

                    let tooltipProjectPrev = document.createElement('div');
                    tooltipProjectPrev.classList.add('kp-group-pop-up-content-description-wrapper-prev-tooltip');
                    tooltipProjectPrev.innerHTML = 'Projet précédent';
                    categoryPopUpContentDescriptionWrapperPrev.appendChild(tooltipProjectPrev);
                    // categoryPopUpContentDescriptionWrapperPrev.innerHTML = `<svg width='40px' height='40px' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg' fill='none'>
                    //       <path stroke='#ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 4l-6 6 6 6'/>
                    //     </svg>`;
                    //
                    //add the svg
                    let svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                    svg.setAttribute('width', '40px');
                    svg.setAttribute('height', '40px');
                    svg.setAttribute('viewBox', '0 0 20 20');
                    svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                    svg.setAttribute('fill', 'none');
                    let path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                    path.setAttribute('stroke', '#ffffff');
                    path.setAttribute('stroke-linecap', 'round');
                    path.setAttribute('stroke-linejoin', 'round');
                    path.setAttribute('stroke-width', '2');
                    path.setAttribute('d', 'M13 4l-6 6 6 6');
                    svg.appendChild(path);
                    categoryPopUpContentDescriptionWrapperPrev.appendChild(svg);


                    categoryPopUpContentDescriptionWrapperPrev.addEventListener('mouseover', function () {
                        this.style.width = '150px';
                        this.style.maxWidth = '150px';
                        document.querySelector('.kp-group-pop-up-content-description-wrapper-prev-tooltip').classList.add('show-tooltip');
                    });
                    categoryPopUpContentDescriptionWrapperPrev.addEventListener('mouseout', function () {
                        let tooltip = document.querySelector('.kp-group-pop-up-content-description-wrapper-prev-tooltip');
                        this.style.width = '50px';
                        this.style.maxWidth = '50px';
                        if (tooltip !== null) {
                            tooltip.classList.remove('show-tooltip');
                        }
                    });

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
                    //categoryPopUpContent.appendChild(categoryPopUpContentDescriptionWrapper);
                    categoryPopUpContent.appendChild(categoryPopUpContentProjectsWrapper);
                    categoryPopUp.appendChild(categoryPopUpHeader);
                    categoryPopUp.appendChild(categoryPopUpContent);
                    document.getElementById('kp-pop-section').appendChild(categoryPopUp);

                    showProject();
                }


                for (let i = 0; i < categoriesList.length; i++) {
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


add_shortcode('kemet_projects', 'kmt_projects');