(function($) {
    function ReviewFormViewModel() {
        let self = this;
        self.showSection = ko.observable(false);
        self.name = ko.observable('');
        self.title = ko.observable('');
        self.description = ko.observable('');
        self.recommend_level = ko.observable('');
        self.stars = ko.observableArray([1, 2, 3, 4, 5]);
        self.reviews = ko.observableArray([]);
        self.currentPage = ko.observable(1);
        self.totalPages = ko.observable(1);
        self.perPage = 10;
        self.errorMessages = ko.observable({});
    
        self.submitReview = function () {

            let product_id = $("#product_id").val();

            if (!checkReviewCookie(product_id)) {
                alert("同一商品について短時間でのコメント記入はできません。時間をおいて再度投稿してください。");
                return;
            }

            let reviewData = {
                product_id: product_id,
                name: self.name(),
                title: self.title(),
                description: self.description(),
                recommend_level: self.recommend_level(),
            };
    
            console.log(reviewData);
    
            // AJAXリクエストを送信
            $.ajax({
                url: '/apps/cl-review/product_reviews/create',
                type: 'POST',
                data: JSON.stringify(reviewData),
                contentType: 'application/json',
                success: function (response) {
                    setReviewCookie(product_id);
                    alert("レビューが送信されました。管理者承認の元、一覧に表示されます。");
                    self.name("");
                    self.title("");
                    self.description("");
                    self.recommend_level(0);
                    self.loadReviews(self.currentPage()); // 現在のページを再読み込み
                },
                error: function (error) {
                    console.log(error);
                    if(error?.responseJSON?.messages){
                        self.errorMessages(error.responseJSON.messages);
                    }
                }
            });
        };
    
    
    
        self.hasNextPage = ko.computed(function () {
            return self.currentPage() < self.totalPages();
        });
    
        self.hasPreviousPage = ko.computed(function () {
            return self.currentPage() > 1;
        });
    
        self.loadReviews = function (page) {
            $.ajax({
                url: '/apps/cl-review/product_reviews',
                type: 'GET',
                data: {
                    product_id: $("#product_id").val(), 
                    page: page, 
                    perPage: self.perPage
                },
                contentType: 'application/json',
                success: function (response) {
                    //console.log(response.data);
                    self.showSection(true);
                    self.reviews(response.data);
                    self.currentPage(response.meta.currentPage);
                    self.totalPages(response.meta.totalPages);
                },
                error: function (error) {
                    console.error('一覧取得失敗:', error);
                }
            });
        };
    
        self.nextPage = function () {
            if (self.hasNextPage()) {
                self.loadReviews(Number(self.currentPage()) + 1);
            }
        };
    
        self.prevPage = function () {
            if (self.hasPreviousPage()) {
                self.loadReviews(Number(self.currentPage()) - 1);
            }
        };
    
    
      
        self.toggleRating = function(rating) {
            if (self.recommend_level() === rating) {
                self.recommend_level(0); // 再クリック リセット
            } else {
                self.recommend_level(rating);
            }
        };
    
    
        // 初回ロード
        self.loadReviews(self.currentPage());
    }
    
    ko.bindingHandlers.starRating = {
        update: function(element, valueAccessor) {
            const value = ko.unwrap(valueAccessor());
            const maxStars = 5;
            let stars = '';
    
            for (let i = 0; i < maxStars; i++) {
                stars += i < value ? '<span style="color: gold;">★</span>' : '<span style="color: grey;">★</span>';
            }
    
            element.innerHTML = stars;
        }
    };


    function setReviewCookie(productId) {
        const now = new Date();
        now.setTime(now.getTime() + (30 * 60 * 1000)); // 30分間有効
        const expires = "expires=" + now.toUTCString();
        document.cookie = `cl_reviewed_${productId}=${Date.now()};${expires};path=/`;
    }
    

    function checkReviewCookie(productId) {
        const cookies = document.cookie.split(';');
        const reviewCookie = cookies.find(cookie => cookie.trim().startsWith(`cl_reviewed_${productId}=`));
        if (reviewCookie) {
            const lastReviewedTime = parseInt(reviewCookie.split('=')[1]);
            const currentTime = Date.now();
            // 30分以内にレビューがされていた場合は警告
            if (currentTime - lastReviewedTime < 30 * 60 * 1000) {
                return false; // 送信を阻止
            }
        }
        return true;
    }
    
    ko.applyBindings(new ReviewFormViewModel());
    
})(jQuery);