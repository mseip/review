<?php
require_once("../includes/bootstrap.php");
require_once("../includes/Layout/Header.php"); 
?>

<div class="hero min-h-screen">
    <div class="hero-content flex-col lg:flex-row">
        <div>
            <h1 class="sm:text-5xl font-bold text-center max-sm:text-4xl">
                <span>Kanji</span><span class="text-primary">Database</span>
            </h1>

            <form class="text-center py-8 sm:flex gap-2" method="get" action="search.php">
                <label class="input">
                    <svg
                        class="h-[1em] opacity-50"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                    >
                        <g
                            stroke-linejoin="round"
                            stroke-linecap="round"
                            stroke-width="2.5"
                            fill="none"
                            stroke="currentColor"
                        >
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>

                    <input type="text" name="search" required placeholder="Search" />
                </label>

                <input type="submit" class="btn btn-secondary max-sm:btn-outline max-sm:w-full max-sm:mt-4" value="Search" />
            </form>
        </div>
    </div>
</div>

<?php require_once("../includes/Layout/Footer.php"); ?>
