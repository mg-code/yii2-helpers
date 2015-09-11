// Define if not defined
if (mgcode === undefined) {
    var mgcode = {};
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
        }
    };

    return {
        number: numberHelper
    };
})(jQuery);