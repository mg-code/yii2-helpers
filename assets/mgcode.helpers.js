// Define if not defined
if (mgcode === undefined) {
    var mgcode = {};
}

// add indexOf support for IE8 and below
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (what, i) {
        i = i || 0;
        var L = this.length;
        while (i < L) {
            if (this[i] === what) {
                return i;
            }
            ++i;
        }
        return -1;
    };
}

mgcode.helpers = (function ($) {
    var numberHelper = {

        /**
         * Calculate original figure when a percentage increase has been added
         * @param number
         * @param percents
         * @return float
         */
        removeIncreasedPercentage: function (number, percents) {
            var x = 1 + (percents / 100);
            return number / x;
        },

        /**
         * Returns the next lowest float value by rounding up value if necessary.
         * @param number
         * @param decimals
         * @return float
         */
        floor: function (number, decimals) {
            decimals = decimals !== undefined ? decimals : 0;

            var x = '1';
            while (x.length < decimals + 1) {
                x = x + '0';
            }

            var result = Math.floor(number * x) / x;

            // Fix decimal places precision
            result = result.toFixed(decimals);
            return parseFloat(result);
        },

        /**
         * Returns the next lowest float value by rounding up value if necessary.
         * @param number
         * @param decimals
         * @return float
         */
        ceil: function (number, decimals) {
            decimals = decimals !== undefined ? decimals : 0;

            var x = '1';
            while (x.length < decimals + 1) {
                x = x + '0';
            }

            var result = Math.ceil(number * x) / x;

            // Fix decimal places precision
            result = result.toFixed(decimals);
            return parseFloat(result);
        },

        /**
         * Rounds number
         * @param number
         * @param decimals
         * @return float
         */
        round: function (number, decimals) {
            decimals = decimals !== undefined ? decimals : 0;

            var x = '1';
            while (x.length < decimals + 1) {
                x = x + '0';
            }

            var result = Math.round(number * x) / x;

            // Fix decimal places precision
            result = result.toFixed(decimals);
            return parseFloat(result);
        },

        /**
         * Returns percentage from number
         * @param number
         * @param percents
         * @return float
         */
        getPercentage: function (number, percents) {
            return number / 100 * percents;
        },

        /**
         * Prepends leading zeros to number
         * @param number
         * @param length
         * @return string
         */
        leadingZeros: function (number, length) {
            number = String(number);
            var leadingZeros = length - number.length;
            if (leadingZeros <= 0) {
                return number;
            }

            var result = '';
            while (result.length < leadingZeros) {
                result = "0" + result;
            }
            result += number;
            return result;
        }
    };

    var requestHelper = {
        ajaxError: function (XHR, textStatus) {
            var err;
            if (XHR.readyState === 0 || XHR.status === 0) {
                return;
            }
            switch (textStatus) {
                case 'timeout':
                    err = 'The request timed out!';
                    break;
                case 'parsererror':
                    err = 'Parser error!';
                    break;
                case 'error':
                    if (XHR.status && !/^\s*$/.test(XHR.status)) {
                        err = 'Error ' + XHR.status;
                    } else {
                        err = 'Error';
                    }
                    if (XHR.responseText && !/^\s*$/.test(XHR.responseText)) {
                        err = err + ': ' + XHR.responseText;
                    }
                    break;
            }

            if (err) {
                alert(err);
            }
        },

        getParam: function (param) {
            var pageUrl= window.location.search.substring(1);
            var vars = pageUrl.split('&');
            for (var i = 0; i < vars.length; i++) {
                var paramName = vars[i].split('=');
                if (paramName[0] == param) {
                    return paramName[1];
                }
            }

        }
    };

    var urlHelper = {
        /**
         * Adds parameter to url, if parameter already exists replaces it
         * @param url
         * @param k
         * @param v
         * @returns string
         */
        addParam: function (url, k, v) {
            if (typeof k == 'object') {
                jQuery.each(k, function (k, v) {
                    url = urlHelper.addParam(url, k, v);
                });
                return url;
            }
            var re = new RegExp("([?|&])" + k + "=.*?(&|$)", "i"),
                separator = url.indexOf('?') !== -1 ? "&" : "?";
            if (url.match(re)) {
                return url.replace(re, '$1' + k + "=" + v + '$2');
            }
            return url + separator + k + "=" + v;
        }
    };

    var timeHelper = {
        /**
         * Returns time in 10:30 format
         * @param timestamp
         * @returns {string}
         */
        formatTime: function (timestamp) {
            if (typeof timestamp == 'undefined') {
                timestamp = timeHelper.getTimestamp();
            }
            var date = new Date(timestamp * 1000),
                hours = date.getHours(),
                minutes = "0" + date.getMinutes();
            return hours + ':' + minutes.substr(-2);
        },

        /**
         * Returns Unix timestamp
         * @param date If null uses current date object
         * @returns {number}
         */
        getTimestamp: function (date) {
            if (typeof date == 'undefined') {
                date = new Date();
            }
            return Math.round(date.getTime() / 1000);
        }
    };

    return {
        number: numberHelper,
        request: requestHelper,
        url: urlHelper,
        time: timeHelper
    };
})(jQuery);