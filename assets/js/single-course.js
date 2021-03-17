function selectTab(tabIndex) {
    var countTab;
    //Hide All Tabs
    for(countTab=1;countTab<=4;countTab++){
        document.getElementById('tab'+ countTab+'Content').style.display="none";
        document.getElementById('tab' + countTab).classList.remove("active-tab");
    }

    //Show the Selected Tab
    document.getElementById('tab' + tabIndex + 'Content').style.display="block";
    document.getElementById('tab' + tabIndex).classList.add("active-tab");
}

// FAQ Accordion
const accordionFaqHeaders = document.querySelectorAll(".faq-accordion-item-header");

accordionFaqHeaders.forEach(accordionFaqHeader => {
    accordionFaqHeader.addEventListener("click", event => {

        //one collapsed Faq at a time!
        const currentlyActiveAccordionFaqHeader = document.querySelector(".faq-accordion-Faq-header.active");
        if(currentlyActiveAccordionFaqHeader && currentlyActiveAccordionFaqHeader!==accordionFaqHeader) {
            currentlyActiveAccordionFaqHeader.classList.toggle("active");
            currentlyActiveAccordionFaqHeader.nextElementSibling.style.maxHeight = 0;
        }

        accordionFaqHeader.classList.toggle("active");
        const accordionFaqBody = accordionFaqHeader.nextElementSibling;
        if(accordionFaqHeader.classList.contains("active")) {
            accordionFaqBody.style.maxHeight = accordionFaqBody.scrollHeight + "px";
        }
        else {
            accordionFaqBody.style.maxHeight = 0;
        }

    });
});

// Curriculum Accordion
const accordionCurrHeaders = document.querySelectorAll(".curr-accordion-item-header");
const toggle_accordion_item = function( accordionCurrHeader ) {
	accordionCurrHeader.classList.toggle("active");
	const accordionCurrBody = accordionCurrHeader.nextElementSibling;
	if(accordionCurrHeader.classList.contains("active")) {
		accordionCurrBody.style.maxHeight = accordionCurrBody.scrollHeight + "px";
	}
	else {
		accordionCurrBody.style.maxHeight = 0;
	}
}

accordionCurrHeaders.forEach(accordionCurrHeader => {
    accordionCurrHeader.addEventListener("click", () => {
		toggle_accordion_item( accordionCurrHeader );
    });
});

let isExpanded = false;
const exandAllBtn = document.getElementById( 'mto-expand-collape-all' );

if ( exandAllBtn ) {
	exandAllBtn.addEventListener( 'click', (e) => {
		isExpanded = ! isExpanded;

		accordionCurrHeaders.forEach(accordionCurrHeader => {
			toggle_accordion_item( accordionCurrHeader );
		});

		e.currentTarget.innerText = isExpanded ? 'Collapse All Lessons' : 'Expand All Lessons';
	});
}
