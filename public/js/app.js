function addEventListeners() {
  let memberSearchbar = document.getElementById("memberSearchbar");
  let homeSearchbar = document.getElementById("homeSearchbar");
  let forumSearchBar = document.getElementById("forumSearchbar");
  let taksListSearchbar = document.getElementById('taksListSearchbar');
  let taksSearchbar = document.getElementById('taksSearchbar');
  let SearchButton = document.getElementById("searchBtn");

  if (memberSearchbar != null)
    if (SearchButton != null)
      SearchButton.addEventListener("click", sendMemberSearch);

  if (homeSearchbar != null)
    if (SearchButton != null)
      SearchButton.addEventListener("click", sendProjectSearch);
      
  if (forumSearchBar != null)
    if (SearchButton != null)
      SearchButton.addEventListener("click", sendForumSearch);

  if (taksListSearchbar != null)
    if(SearchButton != null)
      SearchButton.addEventListener('click', sendtaskListSearch);

  if (taksSearchbar != null)
    if(SearchButton != null)
      SearchButton.addEventListener('click', sendtasksSearch);
  
}

function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data)
    .map(function (k) {
      return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]);
    })
    .join("&");
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader(
    "X-CSRF-TOKEN",
    document.querySelector('meta[name="csrf-token"]').content
  );
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  request.addEventListener("load", handler);
  request.send(encodeForAjax(data));
}

function sendMemberSearch(event) {
  let id = document.getElementById("projectId").value;
  let filter = document.getElementById("table_filter").value;
  let radios = document.getElementsByName("radios");
  let filter2 = "name";

  for (let i = 0, length = radios.length; i < length; i++) {
    if (radios[i].checked) {
      filter2 = radios[i].value;
      break;
    }
  }
  clearTeams();
  clearMembers();
  if (filter2 == "members") {
    sendAjaxRequest("post", "/api/projects/members/members/",{ name: filter, id: id },memberFilterHandler);
  } else if (filter2 == "teams") {
    sendAjaxRequest("post", "/api/projects/members/teams/", { name: filter, id: id }, teamFilterHandler);
  } else {
    sendAjaxRequest("post", "/api/projects/members/members/", { name: filter, id: id }, memberFilterHandler);
    sendAjaxRequest("post", "/api/projects/members/teams/", { name: filter, id: id }, teamFilterHandler);
  }
  event.preventDefault();
}

function clearTeams() {
  let teams = document.getElementById("addTeamsNext");
  while (teams.nextElementSibling != null)
    teams.nextElementSibling.remove();
}

function clearMembers() {
  let members = document.getElementById("addMembersNext");
  while (members.nextElementSibling != null)
    members.nextElementSibling.remove();
}

function memberFilterHandler() {
  let members = document.getElementById("addMembersNext");
  members.outerHTML = members.outerHTML + this.responseText;
  $('.selectpicker').selectpicker();
}

function teamFilterHandler() {
  let teams = document.getElementById("addTeamsNext");
  teams.outerHTML = teams.outerHTML + this.responseText;
  $('.selectpicker').selectpicker();
}

function sendtaskListSearch(event) {
  let filter = document.getElementById('table_filter').value;
  let id = document.getElementById('projectId').value;

    sendAjaxRequest('post', '/api/taskLists/search', {name: filter, id: id}, searchtaskListHandler);

  event.preventDefault();
}

function searchtaskListHandler() {
  let tasksList = document.getElementById("tasksListRow");
  while (tasksList.nextElementSibling != null)
  tasksList.nextElementSibling.remove();
  tasksList.outerHTML = tasksList.outerHTML + this.responseText;
  $('.selectpicker').selectpicker();
}


function sendtasksSearch(event) {
  let list_id = document.getElementById('taskListId').value;
  let id = document.getElementById('projectId').value;
  let filter = document.getElementById("table_filter").value;
  let radios = document.getElementsByName("radios");
  let filter2 = "name";

  for (let i = 0, length = radios.length; i < length; i++) {
    if (radios[i].checked) {
      filter2 = radios[i].value;
      break;
    }
  }

  if (filter2 == 'name')
    sendAjaxRequest('post', '/api/tasks/search/name', {name: filter, list_id: list_id, id: id}, searchtasksHandler);
  else if (filter2 == 'category')
    sendAjaxRequest('post', '/api/tasks/search/category', {name: filter, list_id: list_id, id: id}, searchtasksHandler);
  

  event.preventDefault();
}

function searchtasksHandler () {
  
  let tasks = document.getElementById("tasksRow");
  while (tasks.nextElementSibling != null)
  tasks.nextElementSibling.remove();
  tasks.outerHTML = tasks.outerHTML + this.responseText;
  $('.selectpicker').selectpicker();
}

function sendProjectSearch(event) {
  let filter = document.getElementById("table_filter").value;

  sendAjaxRequest(
    "post",
    "/api/projects/",
    { name: filter},
    searchProjectHandler
  );

  event.preventDefault();
}

function searchProjectHandler() {
  let projects = document.getElementById('projects');
  projects.innerHTML = this.responseText;
}

function sendForumSearch(event) {
  let filter = document.getElementById("table_filter").value;
  let id = document.getElementById("projectId").value;

  sendAjaxRequest(
    "post",
    "/api/forum/search",
    { name: filter, idProject:id },
    searchForumHandler
  );

  event.preventDefault();
}

function searchForumHandler(){
  let questions = document.getElementById("allQuestions");
  questions.innerHTML=this.responseText;
}

function addQuestion(idProj) {
  let text = document.getElementById("new-question-text").value;
  document.getElementById("new-question-text").value = "";
  if (text)
    sendAjaxRequest('post', '/api/forum/addQuestion/', { text: text, idProject: idProj }, addQuestion2);
}

function addQuestion2() {
  let questions = document.getElementById("allQuestions");
  questions.innerHTML = questions.innerHTML + this.responseText;
}

function addAnswer(node, idQuestion, idProj) {
  let text = node.parentNode.parentNode.previousElementSibling.firstElementChild.value;
  node.parentNode.parentNode.previousElementSibling.firstElementChild.value = "";
  if (text)
    sendAjaxRequest('post', '/api/forum/addAnswer/', { text: text, idQuestion: idQuestion, node: node, idProject: idProj }, addAnswer2);
}

function addAnswer2() {
  let array_temp = JSON.parse(this.responseText);
  let node = document.getElementById("addAnswerForQuestionId" + array_temp[1]);
  node.outerHTML = array_temp[0] + node.outerHTML;
  let number = document.getElementById("numberOfQuestionId" + array_temp[1]);
  number.innerHTML = parseInt(number.innerHTML) + 1;//update answers count
}

function deleteQuestion(node, idQuestion, idProj) {
  node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.nextElementSibling.remove();//delete answers from page, the database deletes automatically
  node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.remove();
  sendAjaxRequest('post', '/api/forum/deleteQuestion/', { idQuestion: idQuestion, idProject: idProj }, successfullDelete);
}

function deleteAnswer(node, idAnswer, idProj) {
  let answer = node.parentNode.parentNode.parentNode.parentNode.parentNode;
  let number = answer.parentNode.previousElementSibling.firstElementChild.firstElementChild.firstElementChild.firstElementChild.children[1].firstElementChild;
  number.innerHTML = number.innerHTML - 1;//update answers count
  answer.nextElementSibling.remove();
  answer.previousElementSibling.remove();
  answer.remove();
  sendAjaxRequest('post', '/api/forum/deleteAnswer/', { idAnswer: idAnswer, idProject: idProj }, successfullDelete);
}

function successfullDelete() {
  if (this.responseText == 1)
    console.log("Sucessfully deleted question.");
  else if (this.responseText == 2)
    console.log("Sucessfully deleted answer.");
  else console.log("Error deleting." + this.responseText + "this");
}

function createTaskList(p_id) {
  let name = document.getElementById("name").value;
  document.getElementById("name").value="";
  if (name == null || name == "") {
    taskListWarning()
  return;}
  let teams = $("#mySelect").val();
  sendAjaxRequest(
    "post",
    "/api/taskLists/create",
    { name: name, teams: JSON.stringify(teams), project_id: p_id },
    createTaskListHandler
  );
  event.preventDefault();
}

function createTaskListHandler() {
  let array_temp = JSON.parse(this.responseText);
  let element = document.getElementById("tasksListRow").parentNode;
  element.innerHTML=element.innerHTML+array_temp[1];
  $(".taskListSelectPicker"+array_temp[0]).selectpicker('');
}

function createTask(p_id, list_id) {
  let name = document.getElementById("taskName").value;
  let description = document.getElementById("taskDescription").value;
  let category = document.getElementById("taskCategory").value;
  let errors=[];
  if (name == null || name == "")
    errors.push("name");
  if (description == null || description == "")
    errors.push("description");
  if (category == null || category == "")
    errors.push("category");
  
  if (errors.length>0){
    taskWarning(errors);
    return;}

  document.getElementById("taskName").value = "";
  document.getElementById("taskDescription").value = "";
  document.getElementById("taskCategory").value = "";

  sendAjaxRequest(
    "post",
    "/api/tasks/create",
    {
      name: name,
      description: description,
      project_id: p_id,
      list_id: list_id,
      category: category
    },
    createTaskHandler
  );

  event.preventDefault();
}

function createTaskHandler() {
  let array_temp = JSON.parse(this.responseText);
  let element= document.getElementById("tasksRow").parentNode;
  element.innerHTML=element.innerHTML+array_temp[1]; 
  $("#solvedBy_task"+array_temp[0]).selectpicker('');
}


function assignTaskList(project_id, list_id) {

  let teams = $('#assignedTeams' + list_id).val();
  let members = $('#assignedMembers' + list_id).val();

  sendAjaxRequest(
    "post",
    "/api/taskLists/assign",
     {project_id: project_id, list_id: list_id, teams: JSON.stringify(teams), members: JSON.stringify(members) },
    assignTaskListHandler
  );

  event.preventDefault();
}


function assignTaskListHandler() {
  let array_temp = JSON.parse(this.responseText);
  let element = document.getElementById("taskList"+array_temp[0]);
  element.outerHTML= array_temp[1];
  $(".taskListSelectPicker"+array_temp[0]).selectpicker('');
}

function addComment(task_id, project_id) {

  let text = document.getElementById("newComment").value;
  document.getElementById("newComment").value="";
  
  if(text)
  sendAjaxRequest(
    "post",
    "/api/tasks/comment",
    { task_id: task_id, 
      text: text,
    project_id: project_id },
    addCommentHandler
  );

  event.preventDefault();
}

function addCommentHandler() {
  let element=document.getElementById("addCommentBefore");
  element.outerHTML=this.responseText+element.outerHTML;
}

function removeComment(comment_id){
  sendAjaxRequest(
    "post",
    "/api/tasks/comment/remove",
    {comment_id: comment_id },
    removeCommentHandler
  );
}
function removeCommentHandler(){
  let element = document.getElementById("comment"+this.responseText);
  element.remove();
}

function editTask(task_id) {
  let name = document.getElementById("taskName"+task_id).value;
  let description = document.getElementById("taskDescription"+task_id).value;
  let category = document.getElementById("taskCategory"+task_id).value;
  let errors=new Array;
  if (name == null || name == ""){
    errors.push("name");}
  if (description == null || description == ""){
    errors.push("description");}
  if (category == null || category == ""){
    errors.push("category");}
  if (errors.length>0){
    taskWarning(errors);
    return;}

  sendAjaxRequest(
    "post",
    "/api/tasks/edit",
    { task_id: task_id, 
      name: name,
      description: description,
      category: category },
    editTaskHandler
  );

  event.preventDefault();
}

function editTaskHandler() {
  let array_temp = JSON.parse(this.responseText);
  document.getElementById("taskCardName"+array_temp[0]).innerHTML=array_temp[1];
  document.getElementById("taskCardCategory"+array_temp[0]).innerHTML="Category: "+array_temp[2];
}

function taskWarning(errors){
  str="<div id='warning' class='col-12 alert alert-danger alert-dismissible fade show' role='alert' > The atribute"
  if (errors.length>1)str+="s"
  for (let i=0;i<errors.length;i++){
    if (i==0) str+=" "+errors[i];
    else if (i!=errors.length-1)
      str+=", "+errors[i];
    else str+=" and "+errors[i];
  }
  str+=" can't be empty. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span> </button> </div>"
  if (document.getElementById("warning")!=null) $('.alert').alert('close');//delete previous alert
  let element=document.getElementById("tasksRow");
  element.outerHTML=str+element.outerHTML;
  $('.alert').alert()
}

function editTaskList(list_id) {
  let name = document.getElementById("listName"+list_id).value;
  if (name == null || name == "") {
    taskListWarning()
  return;}

  sendAjaxRequest(
    "post",
    "/api/taskLists/edit",
    { list_id: list_id, 
      name: name },
      editTaskListHandler
  );

  event.preventDefault();
}

function editTaskListHandler(){
  let array_temp = JSON.parse(this.responseText);
  let element = document.getElementById("taskListName"+array_temp[0]);
  element.innerHTML= array_temp[1];
}

function taskListWarning(){
  if (document.getElementById("warning")!=null) $('.alert').alert('close');//delete previous alert
  let element=document.getElementById("tasksListRow");
    element.outerHTML="<div id='warning' class='col-12 alert alert-danger alert-dismissible fade show' role='alert' > The name can't be empty. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span> </button> </div>"
    +element.outerHTML;
    $('.alert').alert()
}

function solveTask(task_id) {

  let solver = $('#solvedBy_task' + task_id).val();

  sendAjaxRequest(
    "post",
    "/api/tasks/solve",
    { task_id: task_id, 
      solver: solver },
      solveTaskHandler
  );
}

function solveTaskHandler() {
  let array_temp = JSON.parse(this.responseText);
  let element=document.getElementById("taskCard"+array_temp[0]);
  element.outerHTML=array_temp[1];
}

function formPassword(current_pass, new_pass, new_pass_confirmation) {
  /* some fetch */
  sendAjaxRequest(
    "post",
    "/api/users/updatePassword",
    { current_pass, new_pass, new_pass_confirmation },
    changePasswordHandler
  );
}

function changePasswordHandler() {
  if (this.responseText == 0) {
    let message_suc = document.getElementById("password_change_alert");
    message_suc.innerHTML =
      '<div class="col-12 alert alert-success alert-dismissible fade show" role="alert">Password <strong>successfully updated.</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    let dismiss_btn = document.getElementById("cancel_modal_pass");
    dismiss_btn.click();
  } else if (this.responseText == 1) {
    let message_fail = document.getElementById("pass_alert_wrong");
    message_fail.innerHTML =
      '<div class="col-12 alert alert-danger alert-dismissible fade show" role="alert"><strong>Password</strong> doesn\'t match records<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
  }else{
    let message_fail = document.getElementById("pass_alert_wrong");
    message_fail.innerHTML =
      '<div class="col-12 alert alert-danger alert-dismissible fade show" role="alert">All fields must be filled / New and confirm don\'t match<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
  
  } 
}
function deleteTasklist(list_id){
  sendAjaxRequest(
    "post",
    "/api/taskLists/delete",
    { list_id },
    deleteTaskListHandler
  );
}

function deleteTaskListHandler(){
  let element = document.getElementById("taskList"+this.responseText);
  element.remove();
}

function deleteTask(task_id,proj_id){
  document.getElementById("taskCard"+task_id).remove();
  sendAjaxRequest(
    "post",
    "/api/tasks/remove",
    { task_id ,proj_id},
    deleteTaskHandler
  );
}

function deleteTaskHandler(){
  console.log("Deleted task "+this.responseText+" sucessfully.");
  
}

let submit_pass_btn = document.getElementById("submit_pass_btn");
if (submit_pass_btn) {
  submit_pass_btn.addEventListener("click", function () {
    let current_pass = document.getElementById("current_pass").value;
    let new_pass = document.getElementById("new_pass").value;
    let new_pass_confirmation = document.getElementById("new_pass_confirmation").value;

    formPassword(current_pass, new_pass, new_pass_confirmation);
  });
}

addEventListeners();
