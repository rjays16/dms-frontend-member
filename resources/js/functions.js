export function formatDecimal(value, places) {
    if(isNaN(Number(value))) {
        return value
    }

    return Number(value).toLocaleString("en", {
        minimumFractionDigits: places,
        maximumFractionDigits: places,
    });
}