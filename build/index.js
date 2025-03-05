(()=>{"use strict";const e=window.wp.blocks,i=window.wp.components,t=window.wp.element,o=window.wp.blockEditor,a=window.wp.i18n,r=JSON.parse('{"apiVersion":2,"name":"ai-image/block","title":"AI Vision Block","category":"widgets","icon":"format-image","description":"A block that generates an image using Pollinations API.","keywords":["ai","image","pollinations"],"version":"1.0.0","textdomain":"ai-vision-block","editorScript":"file:./build/index.js","editorStyle":"file:./build/index.css","attributes":{"prompt":{"type":"string","default":""},"imageUrl":{"type":"string","default":""},"imageId":{"type":"number","default":0}},"supports":{"html":false}}'),n=window.ReactJSXRuntime;(0,e.registerBlockType)(r.name,{...r,edit:e=>{const{attributes:r,setAttributes:s}=e,l=(0,o.useBlockProps)(),[c,g]=(0,t.useState)(!1);return(0,n.jsxs)("div",{...l,children:[(0,n.jsx)(i.TextareaControl,{label:(0,a.__)("Prompt","ai-vision-block"),value:r.prompt,onChange:e=>s({prompt:e})}),(0,n.jsx)(i.TextControl,{label:(0,a.__)("Width","ai-vision-block"),value:r.width,onChange:e=>s({width:e})}),(0,n.jsx)(i.TextControl,{label:(0,a.__)("Height","ai-vision-block"),value:r.height,onChange:e=>s({height:e})}),(0,n.jsx)(i.Button,{isPrimary:!0,onClick:async()=>{if(r.prompt){g(!0);try{const e=r.width||1280,i=r.height||1280,t=await fetch("https://image.pollinations.ai/prompt/"+encodeURIComponent(r.prompt)+"?width="+e+"&height="+i+"&nologo=true&enhance=true");if(500===t.status)throw new Error("Server error: Failed to fetch image (500)");if(!t.ok)throw new Error("Failed to fetch image");const o=t.url;s({imageUrl:o})}catch(e){console.error("Error generating image:",e)}finally{g(!1)}}},disabled:c,children:c?(0,a.__)("Generating...","ai-vision-block"):(0,a.__)("Generate Image","ai-vision-block")}),r.imageUrl&&(0,n.jsx)("figure",{children:(0,n.jsx)("img",{src:r.imageUrl,alt:(0,a.__)("Generated Image","ai-vision-block"),style:{width:"100%"}})})]})},save:e=>{const{attributes:i}=e,t=o.useBlockProps.save();return(0,n.jsx)("figure",{...t,className:"wp-block-image size-full",children:i.imageUrl&&(0,n.jsx)("img",{src:i.imageUrl,alt:(0,a.__)("Generated Image","ai-vision-block")})})}})})();