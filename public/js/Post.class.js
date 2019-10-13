class Post {
 constructor(type, path, id, content, category)
 {
  this.a = document.createElement("a");
  this.div = document.createElement("div");
  this.img = document.createElement("img");
  this.iframe = document.createElement("iframe");
  this.type = type;
  this.path = path;
  this.id = id;
  this.content = content;
  this.category = category;
 }
 fetchTwig(){
  this.a.href = this.path;
  this.a.id = this.id;
  this.a.category = this.category;
  this.a.className = "grid-item";
  this.div.className = "posts "+this.category;
  if(this.type == "video") {
   this.videoType();
   this.div.append(this.iframe);
  }
  else {
   this.imageType();
   this.div.append(this.img);
  }
  this.a.append(this.div);
 }
 videoType() {
  this.iframe.src = this.content;
  this.iframe.allowFullscreen = true;
 }
 imageType() {
  this.img.src = this.content;
 }
}