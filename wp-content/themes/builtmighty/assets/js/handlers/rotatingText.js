export default text => {
    const textArray = text.split(',');
    const textLength = textArray.length;
    document.querySelector('.rotate-text').innerHTML = textArray[0];
    let textIndex = 0;
    setInterval(() => {
        if (textIndex < textLength) {
            document.querySelector('.rotate-text').innerHTML = textArray[textIndex];
            textIndex++;
        } else {
            textIndex = 0;
        }
    }, 1000);
}