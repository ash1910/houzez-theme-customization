import{a as A,u as k,e as x}from"./links.Ce9S4kjc.js";import{A as y,T as D}from"./TitleDescription.t6sXdNIu.js";import{C as B}from"./Card.CfeVpmnZ.js";import{C as P}from"./Tabs.B_xDIDmi.js";import{C as O}from"./Tooltip.DhkkBQWW.js";import{P as L}from"./PostTypes.Cef6XkQ_.js";import{a as j}from"./index.B6JTtDta.js";import{v as a,c as l,F as h,J as g,o as s,k as f,l as n,a as o,G as q,C as p,t as e,B as c,q as w,T as E}from"./runtime-dom.esm-bundler.tPRhSV4q.js";import{_ as F}from"./_plugin-vue_export-helper.BN1snXvA.js";import"./default-i18n.DXRQgkn2.js";import"./helpers.CXsRrhc8.js";import"./JsonValues.D25FTfEu.js";import"./MaxCounts.DHV7qSQX.js";import"./RadioToggle.CaTwJt--.js";import"./Caret.BthVBOwE.js";import"./ProBadge.CVd2ImKm.js";import"./RobotsMeta.DNT5yqf9.js";import"./Checkbox.CmdF-nFt.js";import"./Checkmark.DOG99yeO.js";import"./Row.DRnp1mVs.js";import"./SettingsRow.BwYvQArk.js";import"./Editor.CWmTV9I5.js";import"./isEqual.DkU1ezAe.js";import"./_baseIsEqual.MNbeg0L2.js";import"./_getTag.BWQxgJie.js";import"./_baseClone.DejpcsWN.js";import"./_arrayEach.Fgt6pfHj.js";import"./Tags.BawQBqR0.js";import"./postSlug.gaB5T-wi.js";import"./metabox.CnmedXkm.js";import"./cleanForSlug.BVGRQ_59.js";import"./toString.zLSwYOtv.js";import"./_baseTrim.BYZhh0MR.js";import"./_stringToArray.DnK4tKcY.js";import"./_baseSet.rYV3oc6X.js";import"./GoogleSearchPreview.xL31DpGm.js";import"./constants.CPpKID74.js";import"./HtmlTagsEditor.D4eMyBCg.js";import"./UnfilteredHtml.BcJiQf8S.js";import"./Slide.fjAuzpC8.js";import"./TruSeoScore.DmC22Awy.js";import"./Ellipse.Bp5Bh3uu.js";import"./Information.Bv8uKEyF.js";const M={setup(){return{optionsStore:A(),rootStore:k(),settingsStore:x()}},components:{Advanced:y,CoreCard:B,CoreMainTabs:P,CoreTooltip:O,SvgCircleQuestionMark:j,TitleDescription:D},mixins:[L],data(){return{internalDebounce:null,strings:{label:this.$t.__("Label:",this.$td),name:this.$t.__("Slug:",this.$td),postTypes:this.$t.__("Post Types:",this.$td),ctaButtonText:this.$t.__("Unlock Custom Taxonomies",this.$td),ctaDescription:this.$t.sprintf(this.$t.__("%1$s %2$s lets you set the SEO title and description for custom taxonomies. You can also control all of the robots meta and other options just like the default category and tags taxonomies.",this.$td),"AIOSEO","Pro"),ctaHeader:this.$t.sprintf(this.$t.__("Custom Taxonomy Support is a %1$s Feature",this.$td),"PRO")},tabs:[{slug:"title-description",name:this.$t.__("Title & Description",this.$td),access:"aioseo_search_appearance_settings",pro:!1},{slug:"advanced",name:this.$t.__("Advanced",this.$td),access:"aioseo_search_appearance_settings",pro:!1}]}},computed:{taxonomies(){return this.rootStore.aioseo.postData.taxonomies}},methods:{processChangeTab(m,_){this.internalDebounce||(this.internalDebounce=!0,this.settingsStore.changeTab({slug:`${m}SA`,value:_}),setTimeout(()=>{this.internalDebounce=!1},50))}}},N={class:"aioseo-search-appearance-taxonomies"},R={class:"aioseo-description"},V=o("br",null,null,-1),z=o("br",null,null,-1),I=o("br",null,null,-1);function U(m,_,G,r,i,d){const b=a("svg-circle-question-mark"),S=a("core-tooltip"),T=a("core-main-tabs"),C=a("core-card");return s(),l("div",N,[(s(!0),l(h,null,g(d.taxonomies,(t,$)=>(s(),f(C,{key:$,slug:`${t.name}SA`},{header:n(()=>[o("div",{class:q(["icon dashicons",m.getPostIconClass(t.icon)])},null,2),p(" "+e(t.label)+" ",1),c(S,{"z-index":"99999"},{tooltip:n(()=>[o("div",R,[p(e(i.strings.label)+" ",1),o("strong",null,e(t.label),1),V,p(" "+e(i.strings.name)+" ",1),o("strong",null,e(t.name),1),z,p(" "+e(i.strings.postTypes),1),I,o("ul",null,[(s(!0),l(h,null,g(t.postTypes,(u,v)=>(s(),l("li",{key:v},[o("strong",null,e(u),1)]))),128))])])]),default:n(()=>[c(b)]),_:2},1024)]),tabs:n(()=>[c(T,{tabs:i.tabs,showSaveButton:!1,active:r.settingsStore.settings.internalTabs[`${t.name}SA`],internal:"",onChanged:u=>d.processChangeTab(t.name,u)},null,8,["tabs","active","onChanged"])]),default:n(()=>[c(E,{name:"route-fade",mode:"out-in"},{default:n(()=>[(s(),f(w(r.settingsStore.settings.internalTabs[`${t.name}SA`]),{object:t,separator:r.optionsStore.options.searchAppearance.global.separator,options:r.optionsStore.dynamicOptions.searchAppearance.taxonomies[t.name],type:"taxonomies","show-bulk":!1},null,8,["object","separator","options"]))]),_:2},1024)]),_:2},1032,["slug"]))),128))])}const wt=F(M,[["render",U]]);export{wt as default};