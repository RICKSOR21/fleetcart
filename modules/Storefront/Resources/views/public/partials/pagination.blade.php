<ul
    x-data="Pagination"
    @page-changed="changePage($event.detail.page)"
    class="pagination"
>
    <li class="page-item" :class="{ disabled: hasFirst }">
        <button class="page-link" :disabled="hasFirst" @click="prev">
            <i class="las la-angle-left"></i>
        </button>
    </li>

    <li x-show="rangeFirstPage !== 1" class="page-item">
        <button class="page-link" @click="goto(1)">1</button>
    </li>

    <li x-show="rangeFirstPage === 3" class="page-item">
        <button class="page-link" @click="goto(2)">2</button>
    </li>

    <li
        x-show="
            rangeFirstPage !== 1 &&
            rangeFirstPage !== 2 &&
            rangeFirstPage !== 3
        "
        class="page-item disabled"
    >
        <span class="page-link">...</span>
    </li>

    <template x-for="page in range" :key="page">
        <li
            class="page-item"
            :class="{ active: hasActive(page) }"
        >
            <button class="page-link" x-text="page" @click="goto(page)"></button>
        </li>
    </template>

    <li
        x-show="
            rangeLastPage !== totalPage &&
            rangeLastPage !== totalPage - 1 &&
            rangeLastPage !== totalPage - 2
        "
        class="page-item disabled"
    >
        <span class="page-link">...</span>
    </li>

    <li x-show="rangeLastPage === totalPage - 2" class="page-item">
        <button class="page-link" x-text="totalPage - 1" @click="goto(totalPage - 1)"></button>
    </li>

    <template x-if="rangeLastPage !== totalPage">
        <li class="page-item">
            <button class="page-link" x-text="totalPage" @click="goto(totalPage)"></button>
        </li>
    </template>

    <li class="page-item" :class="{ disabled: hasLast }">
        <button
            class="page-link"
            :class="{ disabled: hasLast }"
            @click="next"
        >
            <i class="las la-angle-right"></i>
        </button>
    </li>
</ul>
