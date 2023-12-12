const projectsBtn = document.getElementById("ProjectsBtn");
const projectsBtn2 = document.getElementById("ProjectsBtn2");
const projectsTable = document.getElementById("ProjectsTable");

const teamsBtn = document.getElementById("TeamsBtn");
const teamsBtn2 = document.getElementById("TeamsBtn2");
const teamsTable = document.getElementById("TeamsTable");

const membersBtn = document.getElementById("MembersBtn");
const membersTable = document.getElementById("MembersTable");

const bigMmbrsTable = document.getElementById("AllMmbrsTable");

const addBtn = document.querySelectorAll(".addBtn");
const addTeamBtn = document.getElementById("addTeamBtn");
const createTeam = document.getElementById("createTeam");

const modifyBtn = document.querySelectorAll(".modifyBtn");

function toggleProjects() {
    projectsBtn.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";
    teamsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    projectsBtn2.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";
    teamsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    membersBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    projectsTable.classList.remove("hidden");
    teamsTable.classList.add("hidden");
    membersTable.classList.add("hidden");
    bigMmbrsTable.classList.add("hidden");
    createTeam.classList.add("hidden");
}

function toggleTeams() {
    teamsBtn.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";
    teamsBtn2.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";

    projectsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    projectsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    membersBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    projectsTable.classList.add("hidden");
    teamsTable.classList.remove("hidden");
    membersTable.classList.add("hidden");
    bigMmbrsTable.classList.add("hidden");
    createTeam.classList.add("hidden");
}

function toggleMembers() {
    projectsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    projectsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    teamsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    teamsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    membersBtn.className = "flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer";

    projectsTable.classList.add("hidden");
    teamsTable.classList.add("hidden");
    membersTable.classList.remove("hidden");
    bigMmbrsTable.classList.add("hidden");
    createTeam.classList.add("hidden");
}

function toggleCreateTeams(event) {
    teamsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    teamsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    membersBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    projectsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    projectsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";

    projectsTable.classList.add("hidden");
    teamsTable.classList.add("hidden");
    membersTable.classList.add("hidden");
    bigMmbrsTable.classList.add("hidden");
    createTeam.classList.remove("hidden"); 
    const teamHeader = document.getElementById("teamHeader");
    const modifyBtnHeader = document.getElementById("modifyBtnHeader");
    
    if (event.target.id === "addTeamBtn") {
        document.getElementById("createTeam").action = "createProject.php";
        teamHeader.innerHTML = "Create project";
        modifyBtnHeader.innerHTML = "<i class='fa-solid fa-users-gear mr-3'></i>Create project";

    } else if (event.target.classList.contains("modifyBtn")) {
        document.getElementById("createTeam").action = "modifyProject.php";
        const modifyID = this.getAttribute('data-id');
        const selectedModifyInput = document.getElementById('selectedModify');

        teamHeader.innerHTML = "Modify project";
        modifyBtnHeader.innerHTML = "<i class='fa-solid fa-users-gear mr-3'></i>Modify project";

        selectedModifyInput.value = modifyID;
        console.log("value is" + selectedModifyInput.value);
    }
}

projectsBtn.addEventListener("click", toggleProjects);
teamsBtn.addEventListener("click", toggleTeams);
projectsBtn2.addEventListener("click", toggleProjects);
teamsBtn2.addEventListener("click", toggleTeams);

membersBtn.addEventListener("click", toggleMembers);
addTeamBtn.addEventListener("click", toggleCreateTeams);

modifyBtn.forEach((btn) => {
    btn.addEventListener("click", toggleCreateTeams);
});

addBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
        console.log("hellloooo");
        teamsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
        teamsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    
        membersBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    
        projectsBtn.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
        projectsBtn2.className = "flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer";
    
        bigMmbrsTable.classList.remove("hidden");
        projectsTable.classList.add("hidden");
        teamsTable.classList.add("hidden");
        membersTable.classList.add("hidden");
        createTeam.classList.add("hidden");
    })
});

document.addEventListener('DOMContentLoaded', function () {
    const teamDivs = document.querySelectorAll('.teamSelect');
    const memberDivs = document.querySelectorAll('.memberSelect');
    const selectedTeamInput = document.getElementById('selectedTeam');
    const selectedMemberInput = document.getElementById('selectedMember');
    const submitBtn = document.getElementById("submitBtn");

    submitBtn.disabled = true;

    function updateBtn() {
        if (selectedTeamInput.value && selectedMemberInput.value) {
            submitBtn.disabled = false;
            submitBtn.classList.remove("bg-gray-500");
            submitBtn.classList.add("bg-green-500", "cursor-pointer");
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add("bg-gray-500");
            submitBtn.classList.remove("bg-green-500", "cursor-pointer");
        }
    }

    teamDivs.forEach((teamDiv) => {
        teamDiv.addEventListener('click', function () {
            teamDivs.forEach((div) => {
                div.classList.remove('border-4', 'border-green-500');
            });

            this.classList.add("border-4", "border-green-500");
            const teamId = this.getAttribute('data-id');
            selectedTeamInput.value = teamId;
            console.log('Selected Team ID:', teamId);
            updateBtn();
        });
    });

    memberDivs.forEach((memberDiv) => {
        memberDiv.addEventListener('click', function () {
            memberDivs.forEach((div) => {
                div.classList.remove('border-4', 'border-green-500');
            });

            this.classList.add("border-4", "border-green-500");
            const memberId = this.getAttribute('data-id');
            selectedMemberInput.value = memberId;
            console.log('Selected Member ID:', memberId);
            updateBtn();
        });
    });
});