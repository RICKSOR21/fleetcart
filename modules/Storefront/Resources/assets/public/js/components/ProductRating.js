Alpine.data("ProductRating", ({ rating_percent, reviews }) => ({
    ratingPercent: rating_percent,
    reviewCount: reviews?.length,

    get hasReviewCount() {
        return this.reviewCount !== undefined;
    },
}));
