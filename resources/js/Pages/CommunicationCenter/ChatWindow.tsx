import React, { useState, useEffect } from 'react';
import { router } from '@inertiajs/react';

export default function ChatWindow({ conversation }) {
    const [message, setMessage] = useState('');
    const [messages, setMessages] = useState(conversation?.messages || []);

    useEffect(() => {
        if (conversation?.messages) {
            setMessages(conversation.messages);
        }
        
        // Example WebSockets subscription using Echo
        // window.Echo.private(`conversation.${conversation.id}`)
        //     .listen('MessageSent', (e) => {
        //         setMessages((prev) => [...prev, e.message]);
        //     });
            
    }, [conversation?.id, conversation?.messages]);

    const sendMessage = (e) => {
        e.preventDefault();
        if (!message.trim()) return;

        // router.post(`/communication/${conversation.id}/messages`, { content: message }, { preserveScroll: true });
        
        setMessages([...messages, { 
            id: Date.now(), 
            content_original: message, 
            sender_id: 1, 
            type: 'text' 
        }]);
        setMessage('');
    };

    return (
        <div className="flex flex-col h-full bg-[#f9fbff] relative w-full font-sans">
            {/* Grid Background */}
            <div className="absolute inset-0 z-0 pointer-events-none opacity-40 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:32px_32px]"></div>
            
            <div className="p-4 border-b flex justify-between items-center shadow-sm bg-white z-10">
                <h3 className="text-lg font-semibold text-slate-800">{conversation?.subject || 'Conversation'}</h3>
                <span className="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{conversation?.status}</span>
            </div>
            
            <div className="flex-1 overflow-y-auto p-6 space-y-6 z-10">
                {/* Dummy translated message if no messages exist yet, to show the UI */}
                {messages.length === 0 && (
                    <div className="flex justify-start">
                        <div className="flex flex-col max-w-[75%] space-y-2">
                            <div className="bg-white rounded-2xl shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)] border border-slate-100 p-5">
                                <div className="flex items-center space-x-3 mb-3">
                                    <span className="bg-[#99F1CB] text-[#126841] text-[11px] tracking-wide font-bold px-2.5 py-1 rounded">
                                        ENGLISH (UK)
                                    </span>
                                    <span className="text-[13px] text-slate-400 font-medium">translated</span>
                                </div>
                                <div className="text-[16.5px] text-slate-800 leading-relaxed">
                                    Hello, I need some help with my order. It hasn't arrived yet.
                                </div>
                            </div>
                            
                            <div className="bg-[#F6F7FA] rounded-xl border border-slate-200 px-4 py-3 w-fit shadow-sm">
                                <p className="text-[14px] text-slate-500">
                                    <span className="font-semibold text-slate-400 uppercase tracking-wider text-[11px] mr-2">Original:</span>
                                    Hola, necesito ayuda con mi pedido. Todavía no ha llegado.
                                </p>
                            </div>
                        </div>
                    </div>
                )}

                {messages.map((msg) => {
                    const isOwnMessage = msg.sender_id === 1;

                    if (isOwnMessage) {
                        return (
                            <div key={msg.id} className="flex justify-end">
                                <div className="max-w-[70%] bg-[#2A374E] text-white rounded-2xl rounded-tr-sm px-5 py-3 shadow-sm text-[16px] leading-relaxed">
                                    {msg.content_original}
                                </div>
                            </div>
                        );
                    }

                    return (
                        <div key={msg.id} className="flex justify-start">
                            <div className="flex flex-col max-w-[75%] space-y-2">
                                {/* Translated Bubble */}
                                <div className="bg-white rounded-2xl shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)] border border-slate-100 p-5">
                                    <div className="flex items-center space-x-3 mb-3">
                                        <span className="bg-[#99F1CB] text-[#126841] text-[11px] tracking-wide font-bold px-2.5 py-1 rounded">
                                            {msg.target_language || 'ENGLISH (UK)'}
                                        </span>
                                        <span className="text-[13px] text-slate-400 font-medium">translated</span>
                                    </div>
                                    <div className="text-[16.5px] text-slate-800 leading-relaxed">
                                        {msg.content_translated || msg.content_original}
                                    </div>
                                </div>
                                
                                {/* Original Message Note */}
                                {(msg.content_translated && msg.content_translated !== msg.content_original) && (
                                    <div className="bg-[#F6F7FA] rounded-xl border border-slate-200 px-4 py-3 w-fit shadow-sm">
                                        <p className="text-[14px] text-slate-500">
                                            <span className="font-semibold text-slate-400 uppercase tracking-wider text-[11px] mr-2">Original:</span>
                                            {msg.content_original}
                                        </p>
                                    </div>
                                )}
                            </div>
                        </div>
                    );
                })}
            </div>
            
            <div className="p-6 bg-transparent z-10 border-t border-slate-200 bg-white">
                <form onSubmit={sendMessage} className="relative w-full mx-auto shadow-[0_4px_12px_-2px_rgba(0,0,0,0.05)] rounded-[24px]">
                    <input 
                        type="text" 
                        value={message}
                        onChange={(e) => setMessage(e.target.value)}
                        className="w-full bg-white border border-slate-200 rounded-[24px] pl-6 pr-20 py-4 text-[16px] text-slate-800 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                        placeholder="Type your message..."
                    />
                    <button 
                        type="submit" 
                        disabled={!message.trim()}
                        className="absolute right-2.5 top-1/2 -translate-y-1/2 bg-[#26354B] text-white rounded-[16px] w-12 h-12 flex items-center justify-center hover:bg-[#1a2534] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5 ml-0.5">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    );
}
