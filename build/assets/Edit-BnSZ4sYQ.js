import{l as v,T as k,m as w,c as y,w as c,o as i,b as s,t as a,a as d,d as m,e as g,i as x,p as V,u as l,f as u,g as _}from"./app-0gpYHNfJ.js";import{_ as B}from"./AuthenticatedLayout-DpP4pYyG.js";import{_ as N}from"./InputError-BmnpY-8-.js";const T={class:"pagetitle"},C={class:"breadcrumb"},E={class:"breadcrumb-item"},S={class:"breadcrumb-item active"},D={class:"section dashboard"},L={class:"row"},M={class:"col-lg-12"},$={class:"card"},j={class:"card-body"},A={class:"card-title"},H={class:"row mb-3"},O={for:"inputText",class:"col-sm-2 col-form-label"},U={class:"col-sm-10"},q=["placeholder"],z={class:"text-center"},F=["disabled"],G={key:0,class:"bi bi-save"},I={key:1,class:"spinner-border spinner-border-sm",role:"status","aria-hidden":"true"},Q={__name:"Edit",props:{permission:Object,translations:Array},setup(e){const t=v(!1),r=e,o=k({name:r.permission.name}),h=()=>{t.value=!0,o.put(route("permissions.update",{permission:r.permission.id}),{onSuccess:()=>{t.value=!1},onError:()=>{t.value=!1}})};return(b,n)=>{const f=w("Link");return i(),y(B,{translations:e.translations},{default:c(()=>[s("div",T,[s("h1",null,a(e.translations.permissions),1),s("nav",null,[s("ol",C,[s("li",E,[d(f,{class:"nav-link",href:b.route("dashboard")},{default:c(()=>[m(a(e.translations.Home),1)]),_:1},8,["href"])]),s("li",S,a(e.translations.edit),1)])])]),s("section",D,[s("div",L,[s("div",M,[s("div",$,[s("div",j,[s("h5",A,a(e.translations.edit_permission),1),n[1]||(n[1]=s("br",null,null,-1)),s("form",{onSubmit:g(h,["prevent"]),class:"row g-3"},[s("div",H,[s("label",O,a(e.translations.name),1),s("div",U,[x(s("input",{type:"text",class:"form-control",placeholder:e.translations.name,"onUpdate:modelValue":n[0]||(n[0]=p=>l(o).name=p)},null,8,q),[[V,l(o).name]]),d(N,{message:l(o).errors.name},null,8,["message"])])]),s("div",z,[s("button",{type:"submit",class:"btn btn-primary",disabled:t.value},[m(a(e.translations.update)+"   ",1),t.value?_("",!0):(i(),u("i",G)),t.value?(i(),u("span",I)):_("",!0)],8,F)])],32)])])])])])]),_:1},8,["translations"])}}};export{Q as default};
