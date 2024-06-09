<style>
    .star-rating .star {
        color: #ccc;
    }

    .star-rating .star.selected {
        color: gold;
    }

    .hideSection {
        display: none !important;
    }

    .showSection {
        display: block !important;
    }

    .error {
        font-size: 12px;
        color: red;
        margin-bottom: 15px;
    }

    .review-lists ul>li {
        margin-bottom: 25px;
    }
</style>

<section class="hideSection contents-section" data-bind="css: {'showSection': true}">
    <div style="margin-bottom: 30px;">
        <form data-bind="submit: submitReview">
            <h2 style="margin-bottom: 15px;">レビュー送信フォーム</h2>
            <input type="hidden" id="product_id" value="{{ product.id }}">
            <div>
                <label>ユーザー名</label>
                <input type="text" data-bind="value: name" />

                <div class="error" data-bind="with: errorMessages()['name']">
                    <p data-bind="text: $data"></p>
                </div>
            </div>

            <div>
                <label>タイトル</label>
                <input type="text" data-bind="value: title" />
                <div class="error" data-bind="with: errorMessages()['title']">
                    <p data-bind="text: $data"></p>
                </div>
            </div>
            <div>
                <label>コメント</label>
                <textarea data-bind="value: description"></textarea>

                <div class="error" data-bind="with: errorMessages()['description']">
                    <p data-bind="text: $data"></p>
                </div>
            </div>
            <div>
                <label for="recommend_level">評価</label>
                <div class="star-rating">
                    <!-- ko foreach: { data: stars, as: 'star' } -->
                    <span class="star" data-bind="css: { 'selected': $parent.recommend_level() >= $index() + 1 }, click: function() { $parent.toggleRating($index() + 1); }">★</span>
                    <!-- /ko -->
                </div>

                <div class="error" data-bind="with: errorMessages()['recommend_level']">
                    <p data-bind="text: $data"></p>
                </div>
            </div>
            <div>
                <button type="submit">送信する</button>
            </div>
        </form>
    </div>


    <div class="review-lists">
        <h2 style="margin-bottom: 15px;">レビュー一覧</h2>

        <div data-bind="if: reviews().length === 0">
            <p>この商品のレビュー投稿は、まだありません</p>
        </div>

        <div data-bind="if: reviews().length > 0">
            <ul data-bind="foreach: reviews">
                <li>
                    <h3>ユーザー名：<span data-bind="text: name"></span></h3>
                    <h3>タイトル：<span data-bind="text: title"></span></h3>
                    <div>
                        コメント:<br>
                        <div data-bind="text: description"></div>
                    </div>
                    <p data-bind="starRating: recommend_level"></p>
                    <p>送信日時: <span data-bind="text: created_at"></span></p>
                </li>
            </ul>
            <div data-bind="if: totalPages() > 1">
                <button data-bind="click: prevPage, enable: hasPreviousPage">前へ</button>
                <span data-bind="text: currentPage"></span>/<span data-bind="text: totalPages"></span>
                <button data-bind="click: nextPage, enable: hasNextPage">次へ</button>
            </div>
        </div>
    </div>
</section>
<script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.0/knockout-min.js"></script>