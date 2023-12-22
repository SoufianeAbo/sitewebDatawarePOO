<?php
session_start();

include 'connection.php';
include './includes/user.php';
include './includes/teams.php';
include './includes/projects.php';


$userObj = new User();
$teamObj = new Team();
$projectObj = new Project();

$oldEmail = $_SESSION['email'];

$userObj->initSession($oldEmail);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="./js/prodOwner.js" defer></script>
    <script src="https://kit.fontawesome.com/736a1ef302.js" crossorigin="anonymous"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                    },
                    animation: {
                        "slide-in-right": "slide-in-right 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940)   both",
                        "slide-out-right": "slide-out-right 0.5s cubic-bezier(0.550, 0.085, 0.680, 0.530)   both",
                        "pulsate-fwd": "pulsate-fwd 0.5s ease  infinite both",
                        "roll-in-top": "roll-in-top 0.6s ease   both",
                        "swing-in-top-fwd": "swing-in-top-fwd 2s cubic-bezier(0.175, 0.885, 0.320, 1.275)   both"
                    },
                    keyframes: {
                        "slide-in-right": {
                            "0%": {
                                transform: "translateX(1000px)",
                                opacity: "0"
                            },
                            to: {
                                transform: "translateX(0)",
                                opacity: "1"
                            }
                        },
                        "slide-out-right": {
                            "0%": {
                                transform: "translateX(0)",
                                opacity: "1"
                            },
                            to: {
                                transform: "translateX(1000px)",
                                opacity: "0"
                            }
                        },
                        "pulsate-fwd": {
                            "0%,to": {
                                transform: "scale(1)"
                            },
                            "50%": {
                                transform: "scale(1.1)"
                            }
                        },

                        "roll-in-top": {
                            "0%": {
                                transform: "translateY(-800px) rotate(-540deg)",
                                opacity: "0"
                            },
                            to: {
                                transform: "translateY(0) rotate(0deg)",
                                opacity: "1"
                            }
                        },
                        "swing-in-top-fwd": {
                            "0%": {
                                transform: "rotateX(-100deg)",
                                "transform-origin": "top",
                                opacity: "0"
                            },
                            to: {
                                transform: "rotateX(0deg)",
                                "transform-origin": "top",
                                opacity: "1"
                            }
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');
        .font-family-karla { font-family: karla; }
        .bg-sidebar { background: #1e40af; }
        .cta-btn { color: #1e40af; }
        .upgrade-btn { background: #1947ee; }
        .upgrade-btn:hover { background: #0038fd; }
        .active-nav-link { background: #1947ee; }
        .nav-item:hover { background: #1947ee; }
        .account-link:hover { background: #3d68ff; }
    </style>
</head>

<body class="bg-gray-100 font-family-karla flex">

    <aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
        <div class="p-6">
            <a class="text-white text-3xl font-semibold uppercase hover:text-gray-300"><img src="./img/white3.png" alt=""></a>
            <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center" id = "addTeamBtn">
                <i class="fa-solid fa-folder-plus mr-3"></i>Create project
            </button>
        </div>
        <nav class="text-white text-base font-semibold pt-3">
            <a class="flex items-center active-nav-link text-white py-4 pl-6 nav-item cursor-pointer" id = "TeamsBtn">
                <i class="fa-solid fa-users mr-3"></i>
                Teams
            </a>
            <a class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer" id = "MembersBtn">
                <i class="fa-solid fa-user-group mr-3"></i>
                Members
            </a>
            <a class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item cursor-pointer" id = "ProjectsBtn">
                <i class="fa-solid fa-list-check mr-3"></i>
                Projects
            </a>
        </nav>
        <a href="logout.php" class="absolute w-full upgrade-btn bottom-0 active-nav-link text-white flex items-center justify-center py-4">
            <i class="fas fa-arrow-circle-up mr-3"></i>
            Sign Out 
        </a>
    </aside>

    <div class="w-full flex flex-col h-screen overflow-y-hidden">
        <header class="w-full items-center bg-blue-950 py-2 px-6 hidden sm:flex">
            <div class="w-1/2"></div>
            <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end">
                <button @click="isOpen = !isOpen" class="realtive z-10 w-12 h-12 rounded-full overflow-hidden border-4 border-gray-400 hover:border-gray-300 focus:border-gray-300 focus:outline-none">
                    <?php

                            $image = $_SESSION['image'];
                            echo "<img src='$image'>";
                    ?>
               </button>
                <button x-show="isOpen" @click="isOpen = false" class="h-full w-full fixed inset-0 cursor-default"></button>
                <div x-show="isOpen" class="absolute w-32 bg-white rounded-lg shadow-lg py-2 mt-16">
                    <a href="#" class="block px-4 py-2 account-link hover:text-white">Account</a>
                    <a href="#" class="block px-4 py-2 account-link hover:text-white">Sign Out</a>
                </div>
            </div>
        </header>

        <header x-data="{ isOpen: false }" class="w-full bg-sidebar py-5 px-6 sm:hidden">
            <div class="flex items-center justify-between">
                <a href="index.html" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">DATAWARE</a>
                <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">
                    <i x-show="!isOpen" class="fas fa-bars"></i>
                    <i x-show="isOpen" class="fas fa-times"></i>
                </button>
            </div>

            <nav :class="isOpen ? 'flex': 'hidden'" class="flex flex-col pt-4">
                <a class="flex items-center active-nav-link text-white py-2 pl-4 nav-item" id = "TeamsBtn2">
                    <i class="fa-solid fa-users mr-3"></i>
                    Teams
                </a>
                <a class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item" id = "ProjectsBtn2">
                    <i class="fa-solid fa-list-check mr-3"></i>
                    Projects
                </a>
                <button onclick="window.location.href='logout.php';" class="w-full bg-white cta-btn font-semibold py-2 mt-3 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                    <i class="fas fa-sign-out-alt mr-3"></i> Sign Out
                </button>
            </nav>
        </header>
    
        <div class="w-full overflow-x-hidden border-t flex flex-col">
            <main class="w-full grid grid-flow-row grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 p-6" id = "TeamsTable">
                <h1 class="text-3xl text-black pb-6 col-span-3">Your teams</h1>

                <?php
                    $equipeID = $_SESSION['equipeID'];
                    $currentMemberID = $_SESSION['id'];
                    $teams = $teamObj->getTeams(null);

                    foreach ($teams as $team) {
                        $scrumMasterDetails = $teamObj->getScrumMasterDetails($team['scrumMasterID']);
                    
                        echo '<div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow">';
                        echo '<a href="#">';
                        echo "<img class='rounded-t-lg' src='{$team['image']}' alt='' />";
                        echo '</a>';
                        echo '<div class="p-5">';
                        echo '<div class="flex justify-between">';
                        echo '<a href="#" class="flex flex-col">';
                        echo "<h5 class='text-2xl font-bold tracking-tight text-gray-900' class='teamNameHTML' data-id='{$team['teamName']}'>{$team['teamName']}</h5>";
                        echo "<p class='mb-4 text-green-900'><i class='fa-solid fa-user-pen pr-2'></i>{$scrumMasterDetails['firstName']} {$scrumMasterDetails['lastName']}</p>";
                        echo '</a>';
                        echo '';
                        echo "<img src='{$scrumMasterDetails['image']}' alt='' class='w-[14%] h-[14%] rounded-full border-2 border-green-700 relative'>";
                        echo '</div>';
                        echo "<p class='mb-3 font-normal text-gray-700' class='teamDescHTML' data-id='{$team['description']}'>{$team['description']}</p>";
                        echo '<div class="flex flex-row items-center justify-between">';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                ?>
            </main>

            <main class="w-full grid grid-flow-row grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 p-6 hidden" id = "ProjectsTable">
                <h1 class="text-3xl text-black pb-6 col-span-3">Your projects</h1>

                <?php
                    $equipeID = $_SESSION['equipeID'];
                    $sql = "SELECT projectID FROM teams WHERE id = $equipeID";

                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        $projectID = $row['projectID'];
                        $currentMemberID = $_SESSION['id'];

                        $projectsQuery = "SELECT * FROM projects WHERE productOwnerID = $currentMemberID";
                        $projectsResult = $conn->query($projectsQuery);

                        if ($projectsResult->num_rows > 0) {
                            while ($projectsData = $projectsResult->fetch_assoc()) {
                                $projectsImg = $projectsData['image'];
                                $projectsName = $projectsData['name'];
                                $projectsDesc = $projectsData['description'];
                                $projectsScrum = $projectsData['scrumMasterID'];
                                $projectsProd = $projectsData['productOwnerID'];
                                $projectsDateStart = $projectsData['date_start'];
                                $projectsDateEnd = $projectsData['date_end'];
                                $projectsStatus = $projectsData['statut'];

                                $scrumMasterQuery = "SELECT * FROM users WHERE id = $projectsScrum";
                                $scrumMasterResult = $conn->query($scrumMasterQuery);

                                if ($scrumMasterResult->num_rows > 0) {
                                    $scrumMasterData = $scrumMasterResult->fetch_assoc();
                                    $scrumMasterFirstName = $scrumMasterData['firstName'];
                                    $scrumMasterLastName = $scrumMasterData['lastName'];
                                    $scrumMasterImg = $scrumMasterData['image'];
                                } else {
                                    $scrumMasterFirstName = 'No current';
                                    $scrumMasterLastName = 'scrum master.';
                                }

                                $prodMasterQuery = "SELECT * FROM users WHERE id = $projectsProd";
                                $prodMasterResult = $conn->query($prodMasterQuery);

                                if ($prodMasterResult->num_rows > 0) {
                                    $prodMasterData = $prodMasterResult->fetch_assoc();
                                    $prodMasterFirstName = $prodMasterData['firstName'];
                                    $prodMasterLastName = $prodMasterData['lastName'];
                                    $prodMasterImg = $prodMasterData['image'];
                                } else {
                                    $prodMasterFirstName = 'N/A';
                                    $prodMasterLastName = 'N/A';
                                }

                                echo '<div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow">';
                                echo '<a href="#">';
                                echo "<img class='rounded-t-lg' src='$projectsImg' alt='' />";
                                echo '</a>';
                                echo '<div class="p-5">';
                                echo '<div class="flex justify-between">';
                                echo '<a href="#" class="flex flex-col">';
                                echo "<h5 class='text-2xl font-bold tracking-tight text-gray-900'>$projectsName</h5>";
                                echo "<p class='text-red-900'><i class='fa-solid fa-user-gear pr-2'></i>$prodMasterFirstName $prodMasterLastName</p>";
                                echo "<p class='mb-4 text-green-900'><i class='fa-solid fa-user-pen pr-2'></i>$scrumMasterFirstName $scrumMasterLastName</p>";
                                echo '</a>';
                                echo '';
                                echo "<img src='$prodMasterImg' alt='' class='w-[14%] h-[14%] rounded-full border-2 border-red-700 relative'>";
                                echo '</div>';
                                echo "<p class='mb-3 font-normal text-gray-700'>$projectsDesc</p>";
                                echo '<div class="flex flex-row items-center justify-between">';
                                echo '<div class = "flex flex-col gap-2">';
                                echo '<div class = "flex flex-row gap-2">';
                                echo '<div>';
                                echo '<a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 addBtn">';
                                echo '<i class="fa-solid fa-user-pen mr-2"></i>Assign';
                                echo '</a>';
                                echo '</div>';
                                echo '<div>';
                                echo '<a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-orange-700 rounded-lg hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 modifyBtn" data-id="'. $projectsData['id'] .'">';
                                echo '<i class="fa-solid fa-gear mr-2"></i>Modify';
                                echo '</a>';
                                echo '</div>';
                                echo '</div>';
                                echo '<div>';
                                echo '<a href="deleteProject.php?id='. $projectsData['id'] .'" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 addBtn">';
                                echo '<i class="fa-solid fa-trash mr-2"></i>Delete';
                                echo '</a>';
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="flex flex-col items-center">';
                                echo "<p class='text-gray-500'>$projectsDateStart</p>";
                                echo "<p class='text-gray-500'>$projectsDateEnd</p>";
                                if ($projectsStatus == 'Active') {
                                    echo '<p class="text-green-500">Active</p>';
                                }
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {

                        }
                    }
                    ?>
            </main>

            <main class="w-full grid grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-5 p-6 justify-center items-center hidden gap-5" id = "MembersTable">
                <h1 class="text-3xl text-black pb-6 col-span-1 md:col-span-2 lg:col-span-5">Members</h1>
                <?php
                  $teams = $teamObj->getTeams('');

                  foreach ($teams as $row) {
                      $teamId = $row['id'];
                      $teamName = $row['teamName'];
                      $teamImg = $row['image'];
                  
                      echo '
                          <div class="w-full h-48 col-span-1 md:col-span-2 lg:col-span-5 bg-white border border-gray-200 rounded-lg shadow sticky top-0" style="background-image: url(' . $teamImg . '); background-position-x: center; background-position-y: 20%; background-repeat: no-repeat; background-size: cover;">
                              <div class="w-full h-fit bg-gray-800 py-2 rounded-t">
                                  <p class="text-white text-center">' . $teamName . '</p>
                              </div>
                          </div>';
                  
                      $members = $userObj->getAllUsersFromTeam($teamId);
                  
                      foreach ($members as $MembersData) {
                          if (!empty($members)) {
                              $MembersFirstName = $MembersData['firstName'];
                              $MembersLastName = $MembersData['lastName'];
                              $MembersID = $MembersData['id'];
                              $MembersImg = $MembersData['image'];
                              $MembersRole = $MembersData['role'];
                  
                              echo '<div class="w-full max-w-sm bg-white border border-gray-100 rounded-lg shadow">';
                              echo '    <div class="flex flex-col items-center pb-2">';
                              echo '        <div class="flex flex-row justify-between px-2 py-2 mb-2 bg-gray-800 rounded-t border border-gray-100">';
                              echo '            <p class="text-white font-bold"><i class="fa-solid fa-flag mr-2"></i>' . $teamName . '</p>';
                              echo '            <img src="' . $teamImg . '" alt="" class="rounded-full h-1/6 w-1/6">';
                              echo '        </div>';
                              echo '        <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="' . $MembersImg . '" alt="' . $MembersFirstName . ' ' . $MembersLastName . '"/>';
                              echo '        <h5 class="mb-1 text-xl font-medium text-' . $userObj->getRoleColor($MembersRole) . '-900">' . $MembersFirstName . ' ' . $MembersLastName . '</h5>';
                              echo '        <span class="text-sm text-' . $userObj->getRoleColor($MembersRole) . '-500"><i class="' . $userObj->getRoleIcon($MembersRole) . '"></i>' . $userObj->getRoleName($MembersRole) . '</span>';
                              echo '        <form method="POST" action="changeUser.php" class="mt-2">';
                              echo '            <input type="hidden" name="userId" value=' . $MembersID . '>';
                              echo '            <select id="dropdownRole" name="dropdownRole" class="border-blue-500 border-2 p-2 rounded" onchange="this.form.submit()">';
                              echo '                <option value="' . $userObj->getRoleName($MembersRole) . '" disabled selected>' . $userObj->getRoleName($MembersRole) . '</option>';
                              if ($MembersRole === "user") {
                                  echo '                <option value="scrumMaster">Scrum Master</option>';
                              } else if ($MembersRole === "scrumMaster") {
                                  echo '                <option value="user">User</option>';
                              }
                              echo '            </select>';
                              echo '        </form>';
                              echo '    </div>';
                              echo '    <div class="flex pb-2 justify-center">';
                              echo '    </div>';
                              echo '</div>';
                          } else {
                              $MembersFirstName = 'N/A';
                              $MembersLastName = 'N/A';
                          }
                      }
                  }
                ?>

            </main>

            <main>
            <form action = "assignProject.php" method = "POST" class="w-full grid grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-5 p-6 justify-center items-center hidden gap-5" id = "AllMmbrsTable">
            <h1 class="text-3xl text-black pb-6 col-span-1 md:col-span-2 lg:col-span-5">Select a project</h1>
            <?php

                    $equipeID = $_SESSION['equipeID'];
                    $sql = "SELECT projectID FROM teams WHERE id = $equipeID";

                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        $projectID = $row['projectID'];
                        $currentMemberID = $_SESSION['id'];

                        $projectsQuery = "SELECT * FROM projects WHERE productOwnerID = $currentMemberID";
                        $projectsResult = $conn->query($projectsQuery);

                        if ($projectsResult->num_rows > 0) {
                            while ($projectsData = $projectsResult->fetch_assoc()) {
                                $projectsImg = $projectsData['image'];
                                $projectsName = $projectsData['name'];
                                $projectsDesc = $projectsData['description'];
                                $projectsScrum = $projectsData['scrumMasterID'];
                                $projectsProd = $projectsData['productOwnerID'];
                                $projectsDateStart = $projectsData['date_start'];
                                $projectsDateEnd = $projectsData['date_end'];
                                $projectsStatus = $projectsData['statut'];

                                $scrumMasterQuery = "SELECT * FROM users WHERE id = $projectsScrum";
                                $scrumMasterResult = $conn->query($scrumMasterQuery);

                                if ($scrumMasterResult->num_rows > 0) {
                                    $scrumMasterData = $scrumMasterResult->fetch_assoc();
                                    $scrumMasterFirstName = $scrumMasterData['firstName'];
                                    $scrumMasterLastName = $scrumMasterData['lastName'];
                                    $scrumMasterImg = $scrumMasterData['image'];
                                } else {
                                    $scrumMasterFirstName = 'N/A';
                                    $scrumMasterLastName = 'N/A';
                                }

                                $prodMasterQuery = "SELECT * FROM users WHERE id = $projectsProd";
                                $prodMasterResult = $conn->query($prodMasterQuery);

                                if ($prodMasterResult->num_rows > 0) {
                                    $prodMasterData = $prodMasterResult->fetch_assoc();
                                    $prodMasterFirstName = $prodMasterData['firstName'];
                                    $prodMasterLastName = $prodMasterData['lastName'];
                                    $prodMasterImg = $prodMasterData['image'];
                                } else {
                                    $prodMasterFirstName = 'N/A';
                                    $prodMasterLastName = 'N/A';
                                }

                                echo '<div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow teamSelect transition-all" data-id="'. $projectsData['id'] .'">';
                                echo '<a href="#">';
                                echo "<img class='rounded-t-lg' src='$projectsImg' alt='' />";
                                echo '</a>';
                                echo '<div class="p-5">';
                                echo '<div class="flex justify-between">';
                                echo '<a href="#" class="flex flex-col">';
                                echo "<h5 class='text-2xl font-bold tracking-tight text-gray-900'>$projectsName</h5>";
                                echo "<p class='text-red-900'><i class='fa-solid fa-user-gear pr-2'></i>$prodMasterFirstName $prodMasterLastName</p>";
                                echo "<p class='mb-4 text-green-900'><i class='fa-solid fa-user-pen pr-2'></i>$scrumMasterFirstName $scrumMasterLastName</p>";
                                echo '</a>';
                                echo '';
                                echo "<img src='$prodMasterImg' alt='' class='w-[14%] h-[14%] rounded-full border-2 border-red-700 relative'>";
                                echo '</div>';
                                echo "<p class='mb-3 font-normal text-gray-700'>$projectsDesc</p>";
                                echo '<div class="flex flex-row items-center justify-between">';
                                echo '<div>';
                                echo '</svg>';
                                echo '</div>';
                                echo '<div class="flex flex-row items-center">';
                                echo "<p class='text-gray-500'>$projectsDateStart</p>";
                                echo "<p class='text-gray-500'>$projectsDateEnd</p>";
                                if ($projectsStatus == 'Active') {
                                    echo '<p class="text-green-500">Active</p>';
                                }
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {

                        }
                    }
                ?>  
            <h1 class="text-3xl text-black pb-6 col-span-1 md:col-span-2 lg:col-span-5">Select a member</h1>
                <?php
                    $equipeID = $_SESSION['equipeID'];
                    $MembersQuery = "SELECT * FROM users u
                    WHERE u.role = 'scrumMaster'
                    AND u.id NOT IN (
                        SELECT scrumMasterID FROM projects
                        WHERE scrumMasterID IS NOT NULL
                    )";

                    $MembersResult = $conn->query($MembersQuery);

                    while ($MembersData = $MembersResult->fetch_assoc()) {
                        if ($MembersResult->num_rows > 0) {
                            $MembersFirstName = $MembersData['firstName'];
                            $MembersLastName = $MembersData['lastName'];
                            $MembersImg = $MembersData['image'];
                            if ($MembersData['role'] == 'user') {
                                $MembersRole = "User";
                                $MembersIcon = "fa-solid fa-user mr-2";
                                $MembersColor = "gray";
                            } else if ($MembersData['role'] == 'scrumMaster') {
                                $MembersRole = "Scrum Master";
                                $MembersIcon = "fa-solid fa-user-pen pr-2";
                                $MembersColor = "green";
                            } else if ($MembersData['role'] == 'prodOwner') {
                                $MembersRole = "Product Owner";
                                $MembersIcon = "fa-solid fa-user-gear pr-2";
                                $MembersColor = "red";
                            }
                        } else {
                            $MembersFirstName = 'N/A';
                            $MembersLastName = 'N/A';
                        }
                        echo '
                            <div class="w-full max-w-sm bg-white border border-gray-100 rounded-lg shadow memberSelect cursor-pointer transition-all" data-id="'. $MembersData['id'] .'">
                                <div class="flex flex-col items-center py-2">
                                    <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="' . $MembersImg . '" alt="' . $MembersFirstName . ' ' . $MembersLastName . '"/>
                                    <h5 class="mb-1 text-xl font-medium text-'. $MembersColor .'-900">' . $MembersFirstName . ' ' . $MembersLastName . '</h5>
                                    <span class="text-sm text-'. $MembersColor .'-500"><i class="'. $MembersIcon .'"></i>'. $MembersRole .'</span>
                                </div>
                            </div>
                            ';
                    }
                ?>
                <div class = "lg:col-span-5 md:col-span-3 col-span-1">
                <input type="hidden" name="selectedTeam" id="selectedTeam" value="">
                <input type="hidden" name="selectedMember" id="selectedMember" value="">
                <input type="submit" value="Add Member" class="bg-gray-500 p-4 rounded text-white transition-all" disabled id = "submitBtn">
                </div>
                </form>
            </main>

            <main>
                <form action = "createProject.php" method = "POST" class="w-full flex justify-center items-center hidden flex-col" enctype="multipart/form-data" id = "createTeam">
                    <h1 class="text-3xl text-black pb-6 col-span-1 md:col-span-2 lg:col-span-5 py-8" id = "teamHeader">Create project</h1>

                    <div class = "grid grid-rows-2 grid-cols-2 w-4/6 gap-24">
                        <div class = "row-span-2 w-full">
                            <div class = "flex flex-col justify-center items-center w-full gap-5">
                                <div class = "w-full p-4 flex flex-col justify-center items-center shadow-md bg-white">
                                    <label for="formName">Project Name</label>
                                    <input id = "teamNameHeader" class = "border border-gray-200 bg-gray-100 p-4 my-4" name = "formName" type="text" placeholder = "NightCrawlers" required>
                                </div>

                                <div class = "w-full p-4 flex flex-col justify-center items-center shadow-md bg-white">
                                    <label for="formDescription">Project Description</label>
                                    <textarea id = "teamDescHeader" class = "border border-gray-200 bg-gray-100 resize-none p-4 my-4" name="formDescription" placeholder = "Lorem ipsum..." cols="30" rows="5" required></textarea>
                                </div>

                                <div class = "w-full p-4 flex flex-col justify-center items-center shadow-md bg-white">
                                    <label for="formName">Project Deadline</label>
                                    <input id = "teamNameHeader" class = "border border-gray-200 bg-gray-100 p-4 my-4" name = "formDate" type="text" placeholder = "2023-12-06" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500">PNG or JPG (MIN. 1152x768px)</p>
                                    </div>
                                    <input id="dropzone-file" name="teamImage" type="file" class="hidden" accept="image/jpeg, image/png" required />
                                </label>
                            </div> 
                        </div>
                        <input type="hidden" name="selectedModify" id="selectedModify" value="">
                        <button type="submit" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded h-1/6 w-full" id = "modifyBtnHeader"><i class="fa-solid fa-users-gear mr-3"></i>Create Project</button>
                    </div>
                </form>
            </main>
        </div>
        
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
</body>
</html>