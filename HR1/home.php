<?php 
include 'admin/db_connect.php'; 
?>
<style>
#portfolio .img-fluid {
    width: 100%;
    height: 30vh;
    z-index: -1;
    position: relative;
    padding: 1em;
}
.vacancy-list {
    cursor: pointer;
    transition: box-shadow 0.3s ease;
}
.vacancy-list:hover {
    box-shadow: 0 0 15px rgba(0,0,0,0.15);
}
span.hightlight {
    background: yellow;
}
.truncate {
    display: block;
    display: -webkit-box;
    max-width: 100%;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.divider {
    border-top: 2px solid #dee2e6;
    margin: 1rem 0;
}
</style>

<section class="py-5 bg-dark text-white text-center">
    <div class="container">
        <img src="admin/assets/img/1.png" alt="TruLend Logo" class="mb-4" style="max-width: 120px;">
        <h1 class="display-5 fw-bold">Welcome to TruLend MicroFinance</h1>
        <p class="lead mt-3">
            At <strong>TruLend</strong>, we empower communities through accessible micro-financing solutions.
            Discover job opportunities and be part of a growing mission to uplift lives and support dreams.
        </p>
    </div>
</section>


<header class="masthead py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Search for Open Vacancies</h2>
            <p class="lead">Use the search bar below to filter through available positions.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="input-group shadow-sm">
                    <input type="text" class="form-control form-control-lg" placeholder="Search positions..." id="filter">
                    <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                </div>
            </div>
        </div>
    </div>
</header>

<section id="list" class="py-5">
    <div class="container">
        <h3 class="text-center mb-4">Vacancy List</h3>
        <?php
        $vacancy = $conn->query("SELECT * FROM vacancy order by date(date_created) desc ");
        while($row = $vacancy->fetch_assoc()):
            $trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
            unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
            $desc = strtr(html_entity_decode($row['description']),$trans);
            $desc = str_replace(array("<li>", "</li>"), array("", ","), $desc);
        ?>
        <div class="card vacancy-list mb-3 border-0 shadow-sm" data-id="<?php echo $row['id'] ?>">
            <div class="card-body">
                <h4 class="card-title filter-txt fw-bold text-primary"><?php echo $row['position'] ?></h4>
                <p class="mb-2"><strong>Needed:</strong> <?php echo $row['availability'] ?></p>
                <p class="card-text truncate filter-txt text-muted"><?php echo strip_tags($desc) ?></p>
                <hr class="divider" style="max-width: 80%;">
                <p class="mb-0 text-end text-secondary small">Click to view details</p>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<script>
    $('.card.vacancy-list').click(function(){
        location.href = "index.php?page=view_vacancy&id=" + $(this).attr('data-id')
    });

    $('#filter').keyup(function() {
        var filter = $(this).val().toLowerCase();
        $('.card.vacancy-list .filter-txt').each(function(){
            var txt = $(this).text().toLowerCase();
            if (txt.includes(filter)) {
                $(this).closest('.card').show();
            } else {
                $(this).closest('.card').hide();
            }
        });
    });
</script>
