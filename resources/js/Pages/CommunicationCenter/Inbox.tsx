import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import ConversationList from './ConversationList';
import ChatWindow from './ChatWindow';

export default function Inbox({ conversations, initialActiveConversation }) {
    const [activeConversation, setActiveConversation] = useState(initialActiveConversation);

    return (
        <div className="flex h-screen bg-gray-50 overflow-hidden">
            <Head title="Communication Center" />
            
            <div className="w-1/3 border-r bg-white flex flex-col">
                <div className="p-4 border-b">
                    <h2 className="text-xl font-semibold">Inbox</h2>
                </div>
                <div className="flex-1 overflow-y-auto">
                    <ConversationList 
                        conversations={conversations} 
                        activeConversation={activeConversation}
                        onSelect={setActiveConversation} 
                    />
                </div>
            </div>

            <div className="flex-1 bg-white flex flex-col">
                {activeConversation ? (
                    <ChatWindow conversation={activeConversation} />
                ) : (
                    <div className="flex-1 flex items-center justify-center text-gray-400">
                        Select a conversation to start messaging
                    </div>
                )}
            </div>
        </div>
    );
}
