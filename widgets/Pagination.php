<?php


namespace App\widgets;


use App\src\WidgetInterface;

class Pagination implements WidgetInterface
{

    public static function run(int $count = null, int $limit = null, int $offset = null, string $sort = ''): string
    {
        $currentPage = floor(($offset)/$limit) + 1;
        $pagesCount = ceil($count/$limit);

        $content = '
            <nav>
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="/index?limit=' . $limit . '&offset=0&sort=' . $sort . '">
                            Первая
                        </a>
                    </li>';

            if($currentPage > 1) {
                $content .= '<li class="page-item">
                    <a class="page-link" href="/index?limit=' .$limit .'&offset=' . ($offset - $limit) . '&sort=' . $sort . '" aria-label="Предыдущая">
                        <span aria-hidden="true">«</span>
                        <span class="sr-only">Предыдущая</span>
                    </a>
                </li>';
            }

            for ($i = 1; $i <= $pagesCount; $i++) {
                $pageOffset = $i<=>$currentPage ? $offset - ($currentPage - $i) * $limit : $offset + ($i - $currentPage) * $limit;
                $activePage = $i == $currentPage ? ' active disabled' : '';
                $content .= '<li class="page-item' . $activePage .'">
                    <a class="page-link" href="/index?limit=' . $limit .'&offset=' . $pageOffset .'&sort=' . $sort . '">' . $i . '</a>
                </li>';
            }

            if($currentPage < $pagesCount) {
                $content .= '<li class="page-item" >
                    <a class="page-link" href = "/index?limit=' . $limit . '&offset=' . ($offset + $limit) . '&sort=' . $sort . '" aria-label = "Следующая">
                        <span aria-hidden = "true" > »</span>
                        <span class="sr-only" > Следующая</span>
                    </a>
                </li>';
            }

            $content .= '<li class="page-item">
                            <a class="page-link" href="/index?limit=' . $limit . '&offset=' . $pageOffset . '&sort=' . $sort . '">
                                Последняя
                            </a>
                        </li>
                    </ul>
                </nav>';
            return $content;
    }
}