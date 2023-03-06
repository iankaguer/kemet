<!DOCTYPE html>
<html>
<head>
    <title>Custom Slider</title>
</head>
<body>
<div class='slide-container'>
    <div class='custom-slider fade'>
        <div class='slide-index'>1 / 3</div>
        <img class='slide-img' src='https://www.codeur.com/tuto/wp-content/uploads/2021/12/slide1.jpg'>
        <div class='slide-text'>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
    </div>
    <div class='custom-slider fade'>
        <div class='slide-index'>2 / 3</div>
        <img class='slide-img' src='https://www.codeur.com/tuto/wp-content/uploads/2021/12/slide2.jpg'>
        <div class='slide-text'>Nullam luctus aliquam ornare.</div>
    </div>
    <div class='custom-slider fade'>
        <div class='slide-index'>3 / 3</div>
        <img class='slide-img' src='https://www.codeur.com/tuto/wp-content/uploads/2021/12/slide3.jpg'>
        <div class='slide-text'>Praesent lobortis libero sed egestas suscipit.</div>
    </div>
    <a class='prev' onclick='plusSlides(-1)'>❮</a>
    <a class='next' onclick='plusSlides(1)'>❯</a>
</div>
<br>
<div class='slide-dot'>
    <span class='dot' onclick='currentSlide(1)'></span>
    <span class='dot' onclick='currentSlide(2)'></span>
    <span class='dot' onclick='currentSlide(3)'></span>
</div>
</body>
</html>
