export default () => {
    const caseStudies = document.querySelectorAll(".case-studies__item ");
    caseStudies.forEach(study => {
        console.log(study)
        study.addEventListener('mouseover', e => {
            const counters = e.currentTarget.querySelectorAll('.figure__figure')
            const speed = 200;
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    const count = parseInt(counter.innerText);
                    const increment = Math.trunc(target / speed);

                    if (count < target) {
                        counter.innerText = count + increment;
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target;
                    }
                  };
                  updateCount();
            });
            // const figure = e.querySelector('.figure__figure')
            // console.log("figure", figure)
            // return figure.style.backgroundColor = 'red'
        })
    })
}