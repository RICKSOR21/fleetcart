Alpine.data("Pagination", () => ({
    rangeMax: 3,

    get rangeFirstPage() {
        if (this.currentPage === 1) {
            return 1;
        }

        if (this.currentPage === this.totalPage) {
            if (this.totalPage - this.rangeMax < 0) {
                return 1;
            }

            return this.totalPage - this.rangeMax + 1;
        }

        return this.currentPage - 1;
    },

    get rangeLastPage() {
        return Math.min(
            this.rangeFirstPage + this.rangeMax - 1,
            this.totalPage
        );
    },

    get range() {
        let rangeList = [];

        for (
            let page = this.rangeFirstPage;
            page <= this.rangeLastPage;
            page += 1
        ) {
            rangeList.push(page);
        }

        return rangeList;
    },

    get hasFirst() {
        return this.currentPage === 1;
    },

    get hasLast() {
        return this.currentPage === this.totalPage;
    },

    init() {
        if (this.currentPage > this.totalPage) {
            this.$dispatch("page-changed", { page: this.totalPage });
        }
    },

    prev() {
        this.$dispatch("page-changed", { page: this.currentPage - 1 });
    },

    next() {
        this.$dispatch("page-changed", { page: this.currentPage + 1 });
    },

    goto(page) {
        if (this.currentPage !== page) {
            this.$dispatch("page-changed", { page });
        }
    },

    hasActive(page) {
        return page === this.currentPage;
    },
}));
