// Constants
const courseContainer = document.getElementById('course-container');
const spinner = document.querySelector('.spinner');
const previousPageButton = document.getElementById('previous-page');
const nextPageButton = document.getElementById('next-page');
const searchForm = document.getElementById('search-form');
const searchInput = document.getElementById('search-input');
const categoriesSelect = document.querySelectorAll('#categories-selector input[type="checkbox"]');
const categoriesContainer = document.querySelector('#categories-selector');

// Variables
let currentSearch = '';
let maxPageNumber = 1;
let categories = [];

// Event listeners
previousPageButton.addEventListener('click', handlePreviousPageClick);
nextPageButton.addEventListener('click', handleNextPageClick);
searchForm.addEventListener('submit', handleSearchFormSubmit);
categoriesSelect.forEach(checkbox => {
  checkbox.addEventListener('change', handleCategoryChange);
});
categoriesContainer.addEventListener('submit', function (event) {
  event.preventDefault();
  navigateToPage();
});
categoriesContainer.addEventListener('reset', function (event) {
  event.preventDefault();
  categoriesSelect.forEach(checkbox => {
    checkbox.checked = false;
  });
  categories = [];
  navigateToPage();
});

// Event handlers
function handlePreviousPageClick(event) {
  event.preventDefault();
  navigateToPage(getCurrentPage() - 1);
}

function handleNextPageClick(event) {
  event.preventDefault();
  navigateToPage(getCurrentPage() + 1);
}

function handleSearchFormSubmit(event) {
  event.preventDefault();
  currentSearch = searchInput.value;

  const urlParams = new URLSearchParams(window.location.search);
  if (currentSearch) {
    urlParams.set('search', currentSearch);
  } else {
    urlParams.delete('search');
  }
  window.history.replaceState({}, '', '?' + urlParams.toString());

  navigateToPage();
}

function handleCategoryChange(event) {
  const category = event.target.value;

  if (event.target.checked) {
    categories.push(category);
  } else {
    categories = categories.filter(c => c !== category);
  }
}

// Helper functions
function getCurrentPage() {
  const urlParams = new URLSearchParams(window.location.search);
  return parseInt(urlParams.get('page')) || 1;
}

function navigateToPage(page = 1) {
  if (page < 1 || page > maxPageNumber) {
    return;
  }

  const urlParams = new URLSearchParams(window.location.search);
  console.log('URL params: ', urlParams);
  urlParams.set('page', page);

  // Get the search parameter from the URL
  const search = urlParams.get('search');

  window.history.pushState({}, '', '?' + urlParams.toString());
  fetchCourses(page, search);
}

function fetchCourses(page, search = '') {
  spinner.style.display = 'block';
  courseContainer.innerHTML = '';


  let url = `/api/courses/api.php?page=${page}&limit=12&component=udemyCourseId,courseInfo.imageLink,courseInfo.title,instructor.name,courseInfo.category,udemyUrls.courseLink${search ? '&search=' + encodeURIComponent(search) : ''}`;

  // Add categories to the URL parameters if any are selected
  if (categories.length > 0) {
    url += '&category=' + categories.join(',');
  }

  console.log('URL: ', url);

  fetch(url)
    .then(response => response.json())
    .then(data => {
      maxPageNumber = Math.ceil(data.totalCourses / 12);
      displayCourses(data.courses);
    })
    .catch(error => {
      console.error('Error:', error);
      spinner.style.display = 'none';
    });
}

function displayCourses(courses) {
  setTimeout(() => {
    spinner.style.display = 'none';
    courseContainer.innerHTML = '';

    courses.forEach(course => {
      const courseDiv = createCourseDiv(course);
      courseContainer.appendChild(courseDiv);
    });
  }, 1000);
}

function createCourseDiv(course) {
  const courseDiv = document.createElement('div');
  courseDiv.className = 'course-div';

  const udemyCourseLink = course.udemyUrls.courseLink.substring(0, course.udemyUrls.courseLink.indexOf('?')) + 'learn/';
  const courseImageLink = course.courseInfo.imageLink;
  const courseTitle = course.courseInfo.title;
  const instructorName = course.instructor.name;

  courseDiv.innerHTML = `
    <a href="${udemyCourseLink}" target="_blank">
      <img class="course-image" src="${courseImageLink}" alt="${courseTitle}" width="240" height="135" loading="lazy">
    </a>
    <a href="${udemyCourseLink}" target="_blank">
      <h2 class="course-title lc-heading-md">${courseTitle}</h2>
    </a>
    <a href="${udemyCourseLink}" target="_blank">
      <p class="instructor-name lc-text-xs">${instructorName}</p>
    </a>
    <a href="${udemyCourseLink}" target="_blank">
      <hr>
    </a>
    <a href="${udemyCourseLink}" target="_blank">
      <div class="start-course-text">START THE COURSE</div>
    </a>
  `;

  return courseDiv;
}

// Initial fetch
navigateToPage(getCurrentPage());