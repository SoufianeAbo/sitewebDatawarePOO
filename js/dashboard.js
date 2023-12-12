const projectsBtn = document.getElementById("ProjectsBtn");
const projectsBtn2 = document.getElementById("ProjectsBtn2");
const projectsTable = document.getElementById("ProjectsTable");

const teamsBtn = document.getElementById("TeamsBtn");
const teamsBtn2 = document.getElementById("TeamsBtn2");
const teamsTable = document.getElementById("TeamsTable");

const membersBtn = document.getElementById("MembersBtn");
const membersBtn2 = document.getElementById("MembersBtn2");
const membersTable = document.getElementById("MembersTable");

function toggleProjects() {
    projectsBtn.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";
    teamsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    projectsBtn2.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";
    teamsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    membersBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    membersBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    membersTable.classList.add("hidden");
    projectsTable.classList.remove("hidden");
    teamsTable.classList.add("hidden");
}

function toggleTeams() {
    teamsBtn.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";
    teamsBtn2.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";
    projectsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    projectsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    membersBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    membersBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    membersTable.classList.add("hidden");
    projectsTable.classList.add("hidden");
    teamsTable.classList.remove("hidden");
}

function toggleMembers() {
    projectsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    projectsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    teamsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    teamsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    membersBtn.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";
    membersBtn2.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";
    projectsTable.classList.add("hidden");
    teamsTable.classList.add("hidden");
    membersTable.classList.remove("hidden");
}

projectsBtn.addEventListener("click", toggleProjects);
teamsBtn.addEventListener("click", toggleTeams);
projectsBtn2.addEventListener("click", toggleProjects);
membersBtn2.addEventListener("click", toggleMembers);
teamsBtn2.addEventListener("click", toggleTeams);
membersBtn.addEventListener("click", toggleMembers);