"use strict";
! function () {
    let e, t, o, a, i;
    a = (isDarkStyle ? (e = config.colors_dark.cardColor, t = config.colors_dark.textMuted, i = config.colors_dark.bodyColor, o = config.colors_dark.headingColor, config.colors_dark) : (e = config.colors.cardColor, t = config.colors.textMuted, i = config.colors.bodyColor, o = config.colors.headingColor, config.colors)).borderColor;
    var s = {
            donut: {
                series1: config.colors.success,
                series2: "#28c76fb3",
                series3: "#28c76f80",
                series4: config.colors_label.success
            }
        },
        r = document.querySelector("#expensesChart"),
        n = {
            chart: {
                height: 145,
                sparkline: {
                    enabled: !0
                },
                parentHeightOffset: 0,
                type: "radialBar"
            },
            colors: [config.colors.warning],
            series: [78],
            plotOptions: {
                radialBar: {
                    offsetY: 0,
                    startAngle: -90,
                    endAngle: 90,
                    hollow: {
                        size: "65%"
                    },
                    track: {
                        strokeWidth: "45%",
                        background: a
                    },
                    dataLabels: {
                        name: {
                            show: !1
                        },
                        value: {
                            fontSize: "22px",
                            color: o,
                            fontWeight: 500,
                            offsetY: -5
                        }
                    }
                }
            },
            grid: {
                show: !1,
                padding: {
                    bottom: 5
                }
            },
            stroke: {
                lineCap: "round"
            },
            labels: ["Progress"],
            responsive: [{
                breakpoint: 1442,
                options: {
                    chart: {
                        height: 120
                    },
                    plotOptions: {
                        radialBar: {
                            dataLabels: {
                                value: {
                                    fontSize: "18px"
                                }
                            },
                            hollow: {
                                size: "60%"
                            }
                        }
                    }
                }
            }, {
                breakpoint: 1025,
                options: {
                    chart: {
                        height: 136
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                size: "65%"
                            },
                            dataLabels: {
                                value: {
                                    fontSize: "18px"
                                }
                            }
                        }
                    }
                }
            }, {
                breakpoint: 769,
                options: {
                    chart: {
                        height: 120
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                size: "55%"
                            }
                        }
                    }
                }
            }, {
                breakpoint: 426,
                options: {
                    chart: {
                        height: 145
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                size: "65%"
                            }
                        }
                    },
                    dataLabels: {
                        value: {
                            offsetY: 0
                        }
                    }
                }
            }, {
                breakpoint: 376,
                options: {
                    chart: {
                        height: 105
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                size: "60%"
                            }
                        }
                    }
                }
            }]
        },
        r = (null !== r && new ApexCharts(r, n).render(), document.querySelector("#profitLastMonth")),
        n = {
            chart: {
                height: 90,
                type: "line",
                parentHeightOffset: 0,
                toolbar: {
                    show: !1
                }
            },
            grid: {
                borderColor: a,
                strokeDashArray: 6,
                xaxis: {
                    lines: {
                        show: !0,
                        colors: "#000"
                    }
                },
                yaxis: {
                    lines: {
                        show: !1
                    }
                },
                padding: {
                    top: -18,
                    left: -4,
                    right: 7,
                    bottom: -10
                }
            },
            colors: [config.colors.info],
            stroke: {
                width: 2
            },
            series: [{
                data: [0, 25, 10, 40, 25, 55]
            }],
            tooltip: {
                shared: !1,
                intersect: !0,
                x: {
                    show: !1
                }
            },
            tooltip: {
                enabled: !1
            },
            xaxis: {
                labels: {
                    show: !1
                },
                axisTicks: {
                    show: !1
                },
                axisBorder: {
                    show: !1
                }
            },
            yaxis: {
                labels: {
                    show: !1
                }
            },
            markers: {
                size: 3.5,
                fillColor: config.colors.info,
                strokeColors: "transparent",
                strokeWidth: 3.2,
                discrete: [{
                    seriesIndex: 0,
                    dataPointIndex: 5,
                    fillColor: e,
                    strokeColor: config.colors.info,
                    size: 5,
                    shape: "circle"
                }],
                hover: {
                    size: 5.5
                }
            },
            responsive: [{
                breakpoint: 1442,
                options: {
                    chart: {
                        height: 100
                    }
                }
            }, {
                breakpoint: 1025,
                options: {
                    chart: {
                        height: 86
                    }
                }
            }, {
                breakpoint: 769,
                options: {
                    chart: {
                        height: 93
                    }
                }
            }]
        },
        r = (null !== r && new ApexCharts(r, n).render(), document.querySelector("#generatedLeadsChart")),
        n = {
            chart: {
                height: 147,
                width: 130,
                parentHeightOffset: 0,
                type: "donut"
            },
            labels: ["Electronic", "Sports", "Decor", "Fashion"],
            series: [45, 58, 30, 50],
            colors: [s.donut.series1, s.donut.series2, s.donut.series3, s.donut.series4],
            stroke: {
                width: 0
            },
            dataLabels: {
                enabled: !1,
                formatter: function (e, t) {
                    return parseInt(e) + "%"
                }
            },
            legend: {
                show: !1
            },
            tooltip: {
                theme: !1
            },
            grid: {
                padding: {
                    top: 15,
                    right: -20,
                    left: -20
                }
            },
            states: {
                hover: {
                    filter: {
                        type: "none"
                    }
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: "70%",
                        labels: {
                            show: !0,
                            value: {
                                fontSize: "1.375rem",
                                fontFamily: "Public Sans",
                                color: o,
                                fontWeight: 500,
                                offsetY: -15,
                                formatter: function (e) {
                                    return parseInt(e) + "%"
                                }
                            },
                            name: {
                                offsetY: 20,
                                fontFamily: "Public Sans"
                            },
                            total: {
                                show: !0,
                                showAlways: !0,
                                color: config.colors.success,
                                fontSize: ".8125rem",
                                label: "Total",
                                fontFamily: "Public Sans",
                                formatter: function (e) {
                                    return "184"
                                }
                            }
                        }
                    }
                }
            },
            responsive: [{
                breakpoint: 1025,
                options: {
                    chart: {
                        height: 172,
                        width: 160
                    }
                }
            }, {
                breakpoint: 769,
                options: {
                    chart: {
                        height: 178
                    }
                }
            }, {
                breakpoint: 426,
                options: {
                    chart: {
                        height: 147
                    }
                }
            }]
        },
        s = (null !== r && new ApexCharts(r, n).render(), document.querySelector("#totalRevenueChart")),
        r = {
            series: [{
                name: "Earning",
                data: [270, 210, 180, 200, 250, 280, 250, 270, 150]
            }, {
                name: "Expense",
                data: [-140, -160, -180, -150, -100, -60, -80, -100, -180]
            }],
            chart: {
                height: 413,
                parentHeightOffset: 0,
                stacked: !0,
                type: "bar",
                toolbar: {
                    show: !1
                }
            },
            tooltip: {
                enabled: !1
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    columnWidth: "40%",
                    borderRadius: 9,
                    startingShape: "rounded",
                    endingShape: "rounded"
                }
            },
            colors: [config.colors.primary, config.colors.warning],
            dataLabels: {
                enabled: !1
            },
            stroke: {
                curve: "smooth",
                width: 6,
                lineCap: "round",
                colors: [e]
            },
            legend: {
                show: !0,
                horizontalAlign: "right",
                position: "top",
                fontFamily: "Public Sans",
                markers: {
                    height: 12,
                    width: 12,
                    radius: 12,
                    offsetX: -3,
                    offsetY: 2
                },
                labels: {
                    colors: i
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 2
                }
            },
            grid: {
                show: !1,
                padding: {
                    bottom: -8,
                    top: 20
                }
            },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                labels: {
                    style: {
                        fontSize: "13px",
                        colors: t,
                        fontFamily: "Public Sans"
                    }
                },
                axisTicks: {
                    show: !1
                },
                axisBorder: {
                    show: !1
                }
            },
            yaxis: {
                labels: {
                    offsetX: -16,
                    style: {
                        fontSize: "13px",
                        colors: t,
                        fontFamily: "Public Sans"
                    }
                },
                min: -200,
                max: 300,
                tickAmount: 5
            },
            responsive: [{
                breakpoint: 1700,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: "43%"
                        }
                    }
                }
            }, {
                breakpoint: 1441,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: "50%"
                        }
                    },
                    chart: {
                        height: 422
                    }
                }
            }, {
                breakpoint: 1300,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: "50%"
                        }
                    }
                }
            }, {
                breakpoint: 1025,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: "50%"
                        }
                    },
                    chart: {
                        height: 390
                    }
                }
            }, {
                breakpoint: 991,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: "38%"
                        }
                    }
                }
            }, {
                breakpoint: 850,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: "50%"
                        }
                    }
                }
            }, {
                breakpoint: 449,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: "73%"
                        }
                    },
                    chart: {
                        height: 360
                    },
                    xaxis: {
                        labels: {
                            offsetY: -5
                        }
                    },
                    legend: {
                        show: !0,
                        horizontalAlign: "right",
                        position: "top",
                        itemMargin: {
                            horizontal: 10,
                            vertical: 0
                        }
                    }
                }
            }, {
                breakpoint: 394,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: "88%"
                        }
                    },
                    legend: {
                        show: !0,
                        horizontalAlign: "center",
                        position: "bottom",
                        markers: {
                            offsetX: -3,
                            offsetY: 0
                        },
                        itemMargin: {
                            horizontal: 10,
                            vertical: 5
                        }
                    }
                }
            }],
            states: {
                hover: {
                    filter: {
                        type: "none"
                    }
                },
                active: {
                    filter: {
                        type: "none"
                    }
                }
            }
        },
        n = (null !== s && new ApexCharts(s, r).render(), document.querySelector("#budgetChart")),
        s = {
            chart: {
                height: 100,
                toolbar: {
                    show: !1
                },
                zoom: {
                    enabled: !1
                },
                type: "line"
            },
            series: [{
                name: "Last Month",
                data: [20, 10, 30, 16, 24, 5, 40, 23, 28, 5, 30]
            }, {
                name: "This Month",
                data: [50, 40, 60, 46, 54, 35, 70, 53, 58, 35, 60]
            }],
            stroke: {
                curve: "smooth",
                dashArray: [5, 0],
                width: [1, 2]
            },
            legend: {
                show: !1
            },
            colors: [a, config.colors.primary],
            grid: {
                show: !1,
                borderColor: a,
                padding: {
                    top: -30,
                    bottom: -15,
                    left: 25
                }
            },
            markers: {
                size: 0
            },
            xaxis: {
                labels: {
                    show: !1
                },
                axisTicks: {
                    show: !1
                },
                axisBorder: {
                    show: !1
                }
            },
            yaxis: {
                show: !1
            },
            tooltip: {
                enabled: !1
            }
        },
        r = (null !== n && new ApexCharts(n, s).render(), document.querySelector("#reportBarChart")),
        n = {
            chart: {
                height: 230,
                type: "bar",
                toolbar: {
                    show: !1
                }
            },
            plotOptions: {
                bar: {
                    barHeight: "60%",
                    columnWidth: "60%",
                    startingShape: "rounded",
                    endingShape: "rounded",
                    borderRadius: 4,
                    distributed: !0
                }
            },
            grid: {
                show: !1,
                padding: {
                    top: -20,
                    bottom: 0,
                    left: -10,
                    right: -10
                }
            },
            colors: [config.colors_label.primary, config.colors_label.primary, config.colors_label.primary, config.colors_label.primary, config.colors.primary, config.colors_label.primary, config.colors_label.primary],
            dataLabels: {
                enabled: !1
            },
            series: [{
                data: [40, 95, 60, 45, 90, 50, 75]
            }],
            legend: {
                show: !1
            },
            xaxis: {
                categories: ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
                axisBorder: {
                    show: !1
                },
                axisTicks: {
                    show: !1
                },
                labels: {
                    style: {
                        colors: t,
                        fontSize: "13px"
                    }
                }
            },
            yaxis: {
                labels: {
                    show: !1
                }
            },
            responsive: [{
                breakpoint: 1025,
                options: {
                    chart: {
                        height: 190
                    }
                }
            }, {
                breakpoint: 769,
                options: {
                    chart: {
                        height: 250
                    }
                }
            }]
        },
        s = (null !== r && new ApexCharts(r, n).render(), $(".datatable-invoice"));
    s.length && s.DataTable({
        ajax: "json/invoice-list.json",
        columns: [{
            data: ""
        }, {
            data: "invoice_id"
        }, {
            data: "invoice_status"
        }, {
            data: "total"
        }, {
            data: "issued_date"
        }, {
            data: "invoice_status"
        }, {
            data: "action"
        }],
        columnDefs: [{
            className: "control",
            responsivePriority: 2,
            targets: 0,
            render: function (e, t, o, a) {
                return ""
            }
        }, {
            targets: 1,
            render: function (e, t, o, a) {
                return '<a href="app-invoice-preview.html"><span>#' + o.invoice_id + "</span></a>"
            }
        }, {
            targets: 2,
            render: function (e, t, o, a) {
                var i = o.invoice_status,
                    s = o.due_date;
                return "<span data-bs-toggle='tooltip' data-bs-html='true' title='<span>" + i + '<br> <span class="fw-medium">Balance:</span> ' + o.balance + '<br> <span class="fw-medium">Due Date:</span> ' + s + "</span>'>" + {
                    Sent: '<span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30"><i class="ti ti-circle-check ti-sm"></i></span>',
                    Draft: '<span class="badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30"><i class="ti ti-device-floppy ti-sm"></i></span>',
                    "Past Due": '<span class="badge badge-center rounded-pill bg-label-danger w-px-30 h-px-30"><i class="ti ti-info-circle ti-sm"></i></span>',
                    "Partial Payment": '<span class="badge badge-center rounded-pill bg-label-success w-px-30 h-px-30"><i class="ti ti-circle-half-2 ti-sm"></i></span>',
                    Paid: '<span class="badge badge-center rounded-pill bg-label-warning w-px-30 h-px-30"><i class="ti ti-chart-pie ti-sm"></i></span>',
                    Downloaded: '<span class="badge badge-center rounded-pill bg-label-info w-px-30 h-px-30"><i class="ti ti-arrow-down-circle ti-sm"></i></span>'
                } [i] + "</span>"
            }
        }, {
            targets: 3,
            render: function (e, t, o, a) {
                return "$" + o.total
            }
        }, {
            targets: -1,
            title: "Actions",
            orderable: !1,
            render: function (e, t, o, a) {
                return '<div class="d-flex align-items-center"><a href="javascript:;" class="text-body" data-bs-toggle="tooltip" title="Send Mail"><i class="ti ti-mail me-2 ti-sm"></i></a><a href="app-invoice-preview.html" class="text-body" data-bs-toggle="tooltip" title="Preview"><i class="ti ti-eye mx-2 ti-sm"></i></a><div class="d-inline-block"><a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm lh-1"></i></a><div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;" class="dropdown-item">Details</a><a href="javascript:;" class="dropdown-item">Archive</a><div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></div></div></div>'
            }
        }, {
            targets: -2,
            visible: !1
        }],
        order: [
            [1, "asc"]
        ],
        dom: '<"row ms-2 me-3"<"col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-2"l<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start mt-md-0 mt-3"B>><"col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row pe-3 gap-md-2"f<"invoice_status mb-3 mb-md-0">>>t<"row d-flex align-items-center mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6 mt-1"p>>',
        displayLength: 7,
        lengthMenu: [7, 10, 25, 50, 75, 100],
        language: {
            sLengthMenu: "_MENU_",
            search: "",
            searchPlaceholder: "Search Invoice"
        },
        buttons: [{
            text: '<i class="ti ti-plus me-md-2"></i><span class="d-md-inline-block d-none">Create Invoice</span>',
            className: "btn btn-primary waves-effect waves-light",
            action: function (e, t, o, a) {
                window.location = "app-invoice-add.html"
            }
        }],
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function (e) {
                        return "Details of " + e.data().full_name
                    }
                }),
                type: "column",
                renderer: function (e, t, o) {
                    o = $.map(o, function (e, t) {
                        return "" !== e.title ? '<tr data-dt-row="' + e.rowIndex + '" data-dt-column="' + e.columnIndex + '"><td>' + e.title + ":</td> <td>" + e.data + "</td></tr>" : ""
                    }).join("");
                    return !!o && $('<table class="table"/><tbody />').append(o)
                }
            }
        },
        initComplete: function () {
            this.api().columns(5).every(function () {
                var t = this,
                    o = $('<select id="UserRole" class="form-select"><option value=""> Select Status </option></select>').appendTo(".invoice_status").on("change", function () {
                        var e = $.fn.dataTable.util.escapeRegex($(this).val());
                        t.search(e ? "^" + e + "$" : "", !0, !1).draw()
                    });
                t.data().unique().sort().each(function (e, t) {
                    o.append('<option value="' + e + '" class="text-capitalize">' + e + "</option>")
                })
            })
        }
    }), s.on("draw.dt", function () {
        [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (e) {
            return new bootstrap.Tooltip(e, {
                boundary: document.body
            })
        })
    }), setTimeout(() => {
        $(".dataTables_filter .form-control").removeClass("form-control-sm"), $(".dataTables_length .form-select").removeClass("form-select-sm")
    }, 300)
}();
