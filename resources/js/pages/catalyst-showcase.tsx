'use client';

import {
    Alert,
    AlertActions,
    AlertDescription,
    AlertTitle,
} from '@/components/catalyst/alert';
import { Avatar } from '@/components/catalyst/avatar';
import { Badge } from '@/components/catalyst/badge';
import { Button } from '@/components/catalyst/button';
import {
    Checkbox,
    CheckboxField,
    CheckboxGroup,
} from '@/components/catalyst/checkbox';
import {
    Dialog,
    DialogActions,
    DialogBody,
    DialogDescription,
    DialogTitle,
} from '@/components/catalyst/dialog';
import { Divider } from '@/components/catalyst/divider';
import {
    Description,
    Field,
    FieldGroup,
    Fieldset,
    Label,
    Legend,
} from '@/components/catalyst/fieldset';
import { Heading, Subheading } from '@/components/catalyst/heading';
import { Input, InputGroup } from '@/components/catalyst/input';
import { Radio, RadioField, RadioGroup } from '@/components/catalyst/radio';
import { Select } from '@/components/catalyst/select';
import { Switch, SwitchField } from '@/components/catalyst/switch';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/catalyst/table';
import { Code, Strong, Text, TextLink } from '@/components/catalyst/text';
import { Textarea } from '@/components/catalyst/textarea';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { Mail, Search } from 'lucide-react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Catalyst Showcase',
        href: '/catalyst-showcase',
    },
];

export default function CatalystShowcase() {
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [isAlertOpen, setIsAlertOpen] = useState(false);
    const [switchEnabled, setSwitchEnabled] = useState(false);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Catalyst Showcase" />
            <div className="flex flex-1 flex-col gap-8 overflow-x-auto p-4 lg:p-8">
                {/* Page Header */}
                <div>
                    <Heading>Catalyst UI Kit</Heading>
                    <Text className="mt-2">
                        A modern application UI kit built with{' '}
                        <TextLink
                            href="https://tailwindcss.com"
                            target="_blank"
                        >
                            Tailwind CSS
                        </TextLink>{' '}
                        and{' '}
                        <TextLink href="https://headlessui.dev" target="_blank">
                            Headless UI
                        </TextLink>
                        .
                    </Text>
                </div>

                <Divider />

                {/* Buttons Section */}
                <section>
                    <Subheading>Buttons</Subheading>
                    <Text className="mt-1 mb-4">
                        Various button styles and colors.
                    </Text>
                    <div className="flex flex-wrap gap-4">
                        <Button>Default</Button>
                        <Button color="dark/zinc">Dark/Zinc</Button>
                        <Button color="light">Light</Button>
                        <Button color="dark">Dark</Button>
                        <Button color="white">White</Button>
                        <Button color="zinc">Zinc</Button>
                        <Button color="indigo">Indigo</Button>
                        <Button color="cyan">Cyan</Button>
                        <Button color="red">Red</Button>
                        <Button color="orange">Orange</Button>
                        <Button color="amber">Amber</Button>
                        <Button color="yellow">Yellow</Button>
                        <Button color="lime">Lime</Button>
                        <Button color="green">Green</Button>
                        <Button color="emerald">Emerald</Button>
                        <Button color="teal">Teal</Button>
                        <Button color="sky">Sky</Button>
                        <Button color="blue">Blue</Button>
                        <Button color="violet">Violet</Button>
                        <Button color="purple">Purple</Button>
                        <Button color="fuchsia">Fuchsia</Button>
                        <Button color="pink">Pink</Button>
                        <Button color="rose">Rose</Button>
                    </div>
                    <div className="mt-4 flex flex-wrap gap-4">
                        <Button outline>Outline</Button>
                        <Button plain>Plain</Button>
                        <Button disabled>Disabled</Button>
                    </div>
                </section>

                <Divider />

                {/* Badges Section */}
                <section>
                    <Subheading>Badges</Subheading>
                    <Text className="mt-1 mb-4">
                        Colorful status indicators and labels.
                    </Text>
                    <div className="flex flex-wrap gap-2">
                        <Badge>Default</Badge>
                        <Badge color="red">Red</Badge>
                        <Badge color="orange">Orange</Badge>
                        <Badge color="amber">Amber</Badge>
                        <Badge color="yellow">Yellow</Badge>
                        <Badge color="lime">Lime</Badge>
                        <Badge color="green">Green</Badge>
                        <Badge color="emerald">Emerald</Badge>
                        <Badge color="teal">Teal</Badge>
                        <Badge color="cyan">Cyan</Badge>
                        <Badge color="sky">Sky</Badge>
                        <Badge color="blue">Blue</Badge>
                        <Badge color="indigo">Indigo</Badge>
                        <Badge color="violet">Violet</Badge>
                        <Badge color="purple">Purple</Badge>
                        <Badge color="fuchsia">Fuchsia</Badge>
                        <Badge color="pink">Pink</Badge>
                        <Badge color="rose">Rose</Badge>
                    </div>
                </section>

                <Divider />

                {/* Avatars Section */}
                <section>
                    <Subheading>Avatars</Subheading>
                    <Text className="mt-1 mb-4">
                        User profile images and initials.
                    </Text>
                    <div className="flex items-center gap-4">
                        <Avatar
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=64&h=64&fit=crop"
                            className="size-8"
                        />
                        <Avatar
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=64&h=64&fit=crop"
                            className="size-10"
                        />
                        <Avatar
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=64&h=64&fit=crop"
                            className="size-12"
                        />
                        <Avatar
                            initials="JD"
                            className="size-8 bg-zinc-900 text-white dark:bg-white dark:text-zinc-900"
                        />
                        <Avatar
                            initials="AB"
                            className="size-10 bg-indigo-500 text-white"
                        />
                        <Avatar
                            initials="XY"
                            className="size-12 bg-emerald-500 text-white"
                        />
                        <Avatar
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=64&h=64&fit=crop"
                            className="size-10"
                            square
                        />
                        <Avatar
                            initials="SQ"
                            className="size-10 bg-rose-500 text-white"
                            square
                        />
                    </div>
                </section>

                <Divider />

                {/* Form Inputs Section */}
                <section>
                    <Subheading>Form Inputs</Subheading>
                    <Text className="mt-1 mb-4">
                        Various form input components.
                    </Text>
                    <div className="max-w-xl">
                        <Fieldset>
                            <FieldGroup>
                                <Field>
                                    <Label>Name</Label>
                                    <Input
                                        name="name"
                                        placeholder="Enter your name"
                                    />
                                </Field>
                                <Field>
                                    <Label>Email</Label>
                                    <Description>
                                        We'll use this to contact you.
                                    </Description>
                                    <Input
                                        type="email"
                                        name="email"
                                        placeholder="you@example.com"
                                    />
                                </Field>
                                <Field>
                                    <Label>Search</Label>
                                    <InputGroup>
                                        <Search data-slot="icon" />
                                        <Input
                                            name="search"
                                            placeholder="Search..."
                                        />
                                    </InputGroup>
                                </Field>
                                <Field>
                                    <Label>Email with icon</Label>
                                    <InputGroup>
                                        <Mail data-slot="icon" />
                                        <Input
                                            type="email"
                                            name="email_icon"
                                            placeholder="you@example.com"
                                        />
                                    </InputGroup>
                                </Field>
                                <Field>
                                    <Label>Country</Label>
                                    <Select name="country">
                                        <option value="">
                                            Select a country
                                        </option>
                                        <option value="us">
                                            United States
                                        </option>
                                        <option value="ca">Canada</option>
                                        <option value="uk">
                                            United Kingdom
                                        </option>
                                        <option value="au">Australia</option>
                                    </Select>
                                </Field>
                                <Field>
                                    <Label>Message</Label>
                                    <Textarea
                                        name="message"
                                        placeholder="Write your message here..."
                                        rows={4}
                                    />
                                </Field>
                            </FieldGroup>
                        </Fieldset>
                    </div>
                </section>

                <Divider />

                {/* Checkboxes & Radio Section */}
                <section>
                    <Subheading>Checkboxes & Radio Buttons</Subheading>
                    <Text className="mt-1 mb-4">
                        Selection controls for forms.
                    </Text>
                    <div className="flex gap-12">
                        <Fieldset>
                            <Legend>Notifications</Legend>
                            <Text>
                                Select the notifications you'd like to receive.
                            </Text>
                            <CheckboxGroup className="mt-4">
                                <CheckboxField>
                                    <Checkbox
                                        name="email_notifications"
                                        defaultChecked
                                    />
                                    <Label>Email notifications</Label>
                                    <Description>
                                        Get notified via email.
                                    </Description>
                                </CheckboxField>
                                <CheckboxField>
                                    <Checkbox name="sms_notifications" />
                                    <Label>SMS notifications</Label>
                                    <Description>
                                        Get notified via SMS.
                                    </Description>
                                </CheckboxField>
                                <CheckboxField>
                                    <Checkbox
                                        name="push_notifications"
                                        color="emerald"
                                        defaultChecked
                                    />
                                    <Label>Push notifications</Label>
                                    <Description>
                                        Get push notifications on your device.
                                    </Description>
                                </CheckboxField>
                            </CheckboxGroup>
                        </Fieldset>

                        <Fieldset>
                            <Legend>Plan</Legend>
                            <Text>Select your preferred plan.</Text>
                            <RadioGroup
                                name="plan"
                                defaultValue="startup"
                                className="mt-4"
                            >
                                <RadioField>
                                    <Radio value="hobby" />
                                    <Label>Hobby</Label>
                                    <Description>
                                        For personal projects.
                                    </Description>
                                </RadioField>
                                <RadioField>
                                    <Radio value="startup" />
                                    <Label>Startup</Label>
                                    <Description>For small teams.</Description>
                                </RadioField>
                                <RadioField>
                                    <Radio value="enterprise" color="indigo" />
                                    <Label>Enterprise</Label>
                                    <Description>
                                        For large organizations.
                                    </Description>
                                </RadioField>
                            </RadioGroup>
                        </Fieldset>
                    </div>
                </section>

                <Divider />

                {/* Switch Section */}
                <section>
                    <Subheading>Switches</Subheading>
                    <Text className="mt-1 mb-4">
                        Toggle switches for boolean settings.
                    </Text>
                    <div className="max-w-md space-y-4">
                        <SwitchField>
                            <Label>Enable dark mode</Label>
                            <Description>
                                Toggle between light and dark themes.
                            </Description>
                            <Switch
                                name="dark_mode"
                                checked={switchEnabled}
                                onChange={setSwitchEnabled}
                            />
                        </SwitchField>
                        <SwitchField>
                            <Label>Marketing emails</Label>
                            <Description>
                                Receive updates about new features.
                            </Description>
                            <Switch
                                name="marketing"
                                color="emerald"
                                defaultChecked
                            />
                        </SwitchField>
                        <SwitchField>
                            <Label>Disabled switch</Label>
                            <Description>This switch is disabled.</Description>
                            <Switch name="disabled" disabled />
                        </SwitchField>
                    </div>
                </section>

                <Divider />

                {/* Table Section */}
                <section>
                    <Subheading>Tables</Subheading>
                    <Text className="mt-1 mb-4">
                        Display tabular data with style.
                    </Text>
                    <Table className="[--gutter:--spacing(6)]">
                        <TableHead>
                            <TableRow>
                                <TableHeader>Name</TableHeader>
                                <TableHeader>Email</TableHeader>
                                <TableHeader>Role</TableHeader>
                                <TableHeader>Status</TableHeader>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            <TableRow>
                                <TableCell className="font-medium">
                                    John Doe
                                </TableCell>
                                <TableCell>john@example.com</TableCell>
                                <TableCell>Admin</TableCell>
                                <TableCell>
                                    <Badge color="green">Active</Badge>
                                </TableCell>
                            </TableRow>
                            <TableRow>
                                <TableCell className="font-medium">
                                    Jane Smith
                                </TableCell>
                                <TableCell>jane@example.com</TableCell>
                                <TableCell>Editor</TableCell>
                                <TableCell>
                                    <Badge color="green">Active</Badge>
                                </TableCell>
                            </TableRow>
                            <TableRow>
                                <TableCell className="font-medium">
                                    Bob Johnson
                                </TableCell>
                                <TableCell>bob@example.com</TableCell>
                                <TableCell>Viewer</TableCell>
                                <TableCell>
                                    <Badge color="zinc">Inactive</Badge>
                                </TableCell>
                            </TableRow>
                            <TableRow>
                                <TableCell className="font-medium">
                                    Alice Brown
                                </TableCell>
                                <TableCell>alice@example.com</TableCell>
                                <TableCell>Admin</TableCell>
                                <TableCell>
                                    <Badge color="amber">Pending</Badge>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </section>

                <Divider />

                {/* Typography Section */}
                <section>
                    <Subheading>Typography</Subheading>
                    <Text className="mt-1 mb-4">
                        Text styles and formatting.
                    </Text>
                    <div className="space-y-4">
                        <Heading level={1}>Heading Level 1</Heading>
                        <Heading level={2}>Heading Level 2</Heading>
                        <Subheading level={3}>Subheading Level 3</Subheading>
                        <Text>
                            This is regular body text. You can include{' '}
                            <Strong>strong text</Strong> for emphasis, and{' '}
                            <Code>inline code</Code> for technical content.
                            Links can be styled with{' '}
                            <TextLink href="#">TextLink component</TextLink>.
                        </Text>
                    </div>
                </section>

                <Divider />

                {/* Dialog & Alert Section */}
                <section>
                    <Subheading>Dialogs & Alerts</Subheading>
                    <Text className="mt-1 mb-4">
                        Modal dialogs and alert notifications.
                    </Text>
                    <div className="flex gap-4">
                        <Button onClick={() => setIsDialogOpen(true)}>
                            Open Dialog
                        </Button>
                        <Button
                            color="red"
                            onClick={() => setIsAlertOpen(true)}
                        >
                            Open Alert
                        </Button>
                    </div>

                    {/* Dialog */}
                    <Dialog
                        open={isDialogOpen}
                        onClose={() => setIsDialogOpen(false)}
                    >
                        <DialogTitle>Edit Profile</DialogTitle>
                        <DialogDescription>
                            Make changes to your profile information below.
                        </DialogDescription>
                        <DialogBody>
                            <FieldGroup>
                                <Field>
                                    <Label>Full Name</Label>
                                    <Input
                                        name="fullname"
                                        placeholder="John Doe"
                                    />
                                </Field>
                                <Field>
                                    <Label>Email</Label>
                                    <Input
                                        type="email"
                                        name="dialog_email"
                                        placeholder="john@example.com"
                                    />
                                </Field>
                            </FieldGroup>
                        </DialogBody>
                        <DialogActions>
                            <Button
                                plain
                                onClick={() => setIsDialogOpen(false)}
                            >
                                Cancel
                            </Button>
                            <Button onClick={() => setIsDialogOpen(false)}>
                                Save Changes
                            </Button>
                        </DialogActions>
                    </Dialog>

                    {/* Alert */}
                    <Alert
                        open={isAlertOpen}
                        onClose={() => setIsAlertOpen(false)}
                    >
                        <AlertTitle>Delete Account</AlertTitle>
                        <AlertDescription>
                            Are you sure you want to delete your account? This
                            action cannot be undone.
                        </AlertDescription>
                        <AlertActions>
                            <Button plain onClick={() => setIsAlertOpen(false)}>
                                Cancel
                            </Button>
                            <Button
                                color="red"
                                onClick={() => setIsAlertOpen(false)}
                            >
                                Delete
                            </Button>
                        </AlertActions>
                    </Alert>
                </section>

                <Divider />

                {/* Checkbox Colors Section */}
                <section>
                    <Subheading>Checkbox Colors</Subheading>
                    <Text className="mt-1 mb-4">
                        Checkboxes in various colors.
                    </Text>
                    <div className="flex flex-wrap gap-4">
                        <Checkbox defaultChecked />
                        <Checkbox color="red" defaultChecked />
                        <Checkbox color="orange" defaultChecked />
                        <Checkbox color="amber" defaultChecked />
                        <Checkbox color="yellow" defaultChecked />
                        <Checkbox color="lime" defaultChecked />
                        <Checkbox color="green" defaultChecked />
                        <Checkbox color="emerald" defaultChecked />
                        <Checkbox color="teal" defaultChecked />
                        <Checkbox color="cyan" defaultChecked />
                        <Checkbox color="sky" defaultChecked />
                        <Checkbox color="blue" defaultChecked />
                        <Checkbox color="indigo" defaultChecked />
                        <Checkbox color="violet" defaultChecked />
                        <Checkbox color="purple" defaultChecked />
                        <Checkbox color="fuchsia" defaultChecked />
                        <Checkbox color="pink" defaultChecked />
                        <Checkbox color="rose" defaultChecked />
                    </div>
                </section>

                <Divider />

                {/* Switch Colors Section */}
                <section>
                    <Subheading>Switch Colors</Subheading>
                    <Text className="mt-1 mb-4">
                        Switches in various colors.
                    </Text>
                    <div className="flex flex-wrap gap-4">
                        <Switch defaultChecked />
                        <Switch color="red" defaultChecked />
                        <Switch color="orange" defaultChecked />
                        <Switch color="amber" defaultChecked />
                        <Switch color="yellow" defaultChecked />
                        <Switch color="lime" defaultChecked />
                        <Switch color="green" defaultChecked />
                        <Switch color="emerald" defaultChecked />
                        <Switch color="teal" defaultChecked />
                        <Switch color="cyan" defaultChecked />
                        <Switch color="sky" defaultChecked />
                        <Switch color="blue" defaultChecked />
                        <Switch color="indigo" defaultChecked />
                        <Switch color="violet" defaultChecked />
                        <Switch color="purple" defaultChecked />
                        <Switch color="fuchsia" defaultChecked />
                        <Switch color="pink" defaultChecked />
                        <Switch color="rose" defaultChecked />
                    </div>
                </section>
            </div>
        </AppLayout>
    );
}
