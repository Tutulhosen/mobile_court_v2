! function(e) {
    function a(p) { if (r[p]) return r[p].exports; var t = r[p] = { i: p, l: !1, exports: {} }; return e[p].call(t.exports, t, t.exports, a), t.l = !0, t.exports }
    var r = {};
    a.m = e, a.c = r, a.d = function(e, r, p) { a.o(e, r) || Object.defineProperty(e, r, { configurable: !1, enumerable: !0, get: p }) }, a.n = function(e) { var r = e && e.__esModule ? function() { return e.default } : function() { return e }; return a.d(r, "a", r), r }, a.o = function(e, a) { return Object.prototype.hasOwnProperty.call(e, a) }, a.p = "", a(a.s = 207)
}({
    207: function(e, a, r) { e.exports = r(208) },
    208: function(e, a) {
        appealNama = e.exports = {
            getAppealOrderListsInfo: function(e) { return $.ajax({ headers: { "X-CSRF-Token": appealPopulate.token }, url: "/appeal/get/appealnama", method: "post", data: { appealId: e }, dataType: "json" }) },
            printAppealNama: function() {
                var e = "",
                    a = $("#appealId").val();
                appealNama.getAppealOrderListsInfo(a).done(function(a, r, p) {
                    if (a.appealOrderLists.length > 0) { e = appealNama.getAppealNamaReport(a), $("#head").empty(), $("#body").empty(), $("#head").append(e.header), $("#body").append(e.body); var t = window.open(); return newdocument = t.document, newdocument.write($("#appealNamaTemplate").html()), newdocument.close(), setTimeout(function() { t.print() }, 500), !1 }
                    $.alert("আদেশ প্রদান করা হয় নি", "অবহিতকরণ বার্তা")
                })
            },
            getAppealNamaReport: function(e) {
                var a = "",
                    r = "",
                    p = "",
                    t = "";
                return a = appealNama.prepareAppealNamaHeader(e), p = e.appealOrderLists[0].order_detail_table_th, t = e.appealOrderLists[0].order_detail_table_close, r = p + appealNama.prepareAppealNamaBody(e) + t, { header: a, body: r }
            },
            prepareAppealNamaHeader: function(e) { var a = e.appealOrderLists.length; return e.appealOrderLists[a - 1].order_header },
            prepareAppealNamaBody: function(e) { var a = ""; return $.each(e.appealOrderLists, function(e, r) { a += r.order_detail_table_body }), a }
        }
    }
});