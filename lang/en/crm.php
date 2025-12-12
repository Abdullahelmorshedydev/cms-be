<?php

return [
    'module_name' => 'CRM',
    'leads' => 'Leads',
    'contacts' => 'Contacts',
    'deals' => 'Deals',
    'calls' => 'Calls',
    'activities' => 'Activities',
    'transactions' => 'Transactions',
    'pipeline' => 'Pipeline',

    // Lead
    'lead' => 'Lead',
    'lead_number' => 'Lead Number',
    'name' => 'Name',
    'name_en' => 'Name (English)',
    'name_ar' => 'Name (Arabic)',
    'email' => 'Email',
    'phone' => 'Phone',
    'secondary_phone' => 'Secondary Phone',
    'whatsapp' => 'WhatsApp',
    'source' => 'Source',
    'priority' => 'Priority',
    'assigned_to' => 'Assigned To',
    'expected_value' => 'Expected Value',
    'expected_close_date' => 'Expected Close Date',

    // Lead Status
    'lead_status' => [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'proposal' => 'Proposal',
        'negotiation' => 'Negotiation',
        'won' => 'Won',
        'lost' => 'Lost',
    ],

    // Lead Source
    'lead_source' => [
        'website' => 'Website',
        'phone' => 'Phone',
        'social_media' => 'Social Media',
        'referral' => 'Referral',
        'walk_in' => 'Walk-in',
        'campaign' => 'Campaign',
        'other' => 'Other',
    ],

    // Priority
    'priority' => [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ],

    // Contact
    'contact' => 'Contact',
    'contact_number' => 'Contact Number',
    'contact_type' => [
        'student' => 'Student',
        'parent' => 'Parent',
        'corporate' => 'Corporate',
        'individual' => 'Individual',
        'other' => 'Other',
    ],
    'address' => 'Address',
    'city' => 'City',
    'country' => 'Country',
    'birth_date' => 'Birth Date',
    'national_id' => 'National ID',
    'tags' => 'Tags',

    // Deal
    'deal' => 'Deal',
    'deal_number' => 'Deal Number',
    'title' => 'Title',
    'title_en' => 'Title (English)',
    'title_ar' => 'Title (Arabic)',
    'value' => 'Value',
    'discount' => 'Discount',
    'final_value' => 'Final Value',
    'probability' => 'Probability',

    // Deal Stage
    'deal_stage' => [
        'prospecting' => 'Prospecting',
        'qualification' => 'Qualification',
        'proposal' => 'Proposal',
        'negotiation' => 'Negotiation',
        'closed_won' => 'Closed Won',
        'closed_lost' => 'Closed Lost',
    ],

    // Call
    'call' => 'Call',
    'call_number' => 'Call Number',
    'call_type' => [
        'inbound' => 'Inbound',
        'outbound' => 'Outbound',
    ],
    'call_status' => [
        'completed' => 'Completed',
        'missed' => 'Missed',
        'busy' => 'Busy',
        'no_answer' => 'No Answer',
        'scheduled' => 'Scheduled',
    ],
    'duration' => 'Duration',
    'outcome' => 'Outcome',

    // Activity
    'activity' => 'Activity',
    'activity_type' => [
        'call' => 'Call',
        'email' => 'Email',
        'meeting' => 'Meeting',
        'task' => 'Task',
        'note' => 'Note',
        'whatsapp' => 'WhatsApp',
        'sms' => 'SMS',
        'visit' => 'Visit',
        'other' => 'Other',
    ],
    'description' => 'Description',
    'due_date' => 'Due Date',
    'completed_at' => 'Completed At',

    // Transaction
    'transaction' => 'Transaction',
    'transaction_number' => 'Transaction Number',
    'amount' => 'Amount',
    'payment_method' => 'Payment Method',
    'transaction_type' => 'Transaction Type',
    'reference_number' => 'Reference Number',

    // Messages
    'lead_created' => 'Lead created successfully',
    'lead_updated' => 'Lead updated successfully',
    'lead_deleted' => 'Lead deleted successfully',
    'contact_created' => 'Contact created successfully',
    'contact_updated' => 'Contact updated successfully',
    'contact_deleted' => 'Contact deleted successfully',
    'deal_created' => 'Deal created successfully',
    'deal_updated' => 'Deal updated successfully',
    'deal_deleted' => 'Deal deleted successfully',
    'deal_stage_updated' => 'Deal stage updated successfully',
    'call_logged' => 'Call logged successfully',
    'call_ended' => 'Call ended successfully',
    'activity_created' => 'Activity created successfully',
    'activity_completed' => 'Activity marked as completed',
    'transaction_processed' => 'Transaction processed successfully',
    'email_sent' => 'Email sent successfully',
    'email_failed' => 'Failed to send email',
];

